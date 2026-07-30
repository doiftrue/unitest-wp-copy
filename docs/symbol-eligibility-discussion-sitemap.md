# Пригодность символов: Sitemaps

## Область

Документ — предварительный анализ для принятия решения по подсистеме карт сайта
WordPress (`wp-includes/sitemaps/`, `wp-includes/sitemaps.php`).

Связанные документы:
- [symbol-eligibility.md](symbol-eligibility.md) — правила пригодности символов
- [runtime.md](runtime.md) — ограничения рантайма
- [config.md](config.md) — модель конфигурации
- [symbol-eligibility-discussion.md](symbol-eligibility-discussion.md) —
  остальные открытые вопросы по границам рантайма


## Текущее состояние

Уже активно:

- `WP_Sitemaps_Registry{}` — реестр провайдеров в памяти
- `WP_Sitemaps_Provider{}` — базовый класс провайдера
- `WP_Sitemaps_Index{}` — модель индекса карты сайта
- `WP_Sitemaps_Renderer{}` — сборщик XML
- `wp_sitemaps_get_max_urls()` — утилита размера страницы, работающая только через фильтр

Отклонено в `config/classes.php` и `config/not-suitable-files.md`:

- `WP_Sitemaps_Posts` — цепочка запросов постов `WP_Query` / `get_permalink()`
- `WP_Sitemaps_Taxonomies` — цепочка `WP_Term_Query` / `get_term_link()` / `wp_count_terms()`
- `WP_Sitemaps_Users` — цепочка `WP_User_Query` / `get_author_posts_url()`
- `WP_Sitemaps_Stylesheet` — `get_language_attributes()` и жизненный цикл HTTP-вывода

То есть детерминированная, работающая в памяти половина подсистемы уже доступна
самостоятельно. Нерешённой остаётся половина, связанная с жизненным циклом запроса.


## Кандидаты

- `WP_Sitemaps` — `wp-includes/sitemaps/class-wp-sitemaps.php`
- `wp_sitemaps_get_server()`
- `wp_get_sitemap_providers()`
- `wp_register_sitemap_provider()`
- `get_sitemap_url()`


## Почему требуется обсуждение

- `wp_sitemaps_get_server()` создаёт `WP_Sitemaps` и сразу вызывает
  `WP_Sitemaps::init()`.
- `WP_Sitemaps::init()` и `WP_Sitemaps::render_sitemaps()` требуют символов
  системы правил перезаписи и жизненного цикла запроса: `add_rewrite_tag()`,
  `add_rewrite_rule()`, `get_query_var()`, `status_header()`, `wp_safe_redirect()`.
- `wp_get_sitemap_providers()`, `wp_register_sitemap_provider()` и
  `get_sitemap_url()` — тонкие обёртки над `wp_sitemaps_get_server()`, поэтому
  они наследуют ту же цепочку инициализации, хотя их собственные тела сводятся к
  тривиальному обращению к реестру.
- Реализации провайдеров дополнительно требуют живых цепочек запросов постов,
  терминов и пользователей.


## Требуемое решение

Нужно решить, должна ли маршрутизация карт сайта стать поддерживаемым частичным
жизненным циклом запроса. Реестр, базовый класс провайдера, индекс и рендерер
уже доступны независимо.

Более узкая альтернатива, которую стоит рассмотреть: открыть функции работы с
реестром (`wp_register_sitemap_provider()`, `wp_get_sitemap_providers()`) поверх
адаптированного под рантайм `WP_Sitemaps`, который пропускает подключение правил
перезаписи в `init()`, оставив `get_sitemap_url()` и `render_sitemaps()` вне
области поддержки. Структурно это тот же компромисс, что разобран для REST-сервера
в [symbol-eligibility-discussion-rest-api.md](symbol-eligibility-discussion-rest-api.md),
и решать его следует согласованно с принятой там политикой границ.
