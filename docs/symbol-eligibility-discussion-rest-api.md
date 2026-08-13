# Пригодность символов: REST API

## Область

Документ — предварительный анализ для принятия решения о переносе слоя
регистрации и диспетчеризации маршрутов WordPress REST API в изолированный
рантайм проекта.

Что нужно поддержать в тестах:

- регистрацию маршрутов через `register_rest_route()`;
- прямую диспетчеризацию в памяти через `rest_do_request()` /
  `WP_REST_Server::dispatch()` (без HTTP, без `serve_request()`);
- проверки статуса, данных, заголовков и ссылок `WP_REST_Response`;
- проверку валидации и санитизации аргументов по схеме через
  `WP_REST_Request::has_valid_params()` / `sanitize_params()`;
- написание собственных контроллеров на базе `WP_REST_Controller`.

Вне области для всех вариантов ниже: обслуживание живых запросов, авторизация
через куки и пароли приложений, ядровые контроллеры эндпоинтов
(`create_initial_rest_routes()`), правила перезаписи и сетевые транспорты.

`WP_REST_Search_Handler` (`wp-includes/rest-api/search/class-wp-rest-search-handler.php`)
также вне области. Сам по себе это крошечный абстрактный базовый класс (две
константы, аксессоры `$type`/`$subtypes`, три абстрактных метода) без собственных
блокеров, но он имеет смысл только вместе с поддерживаемой подсистемой REST-поиска:
все ядровые реализации (`WP_REST_Post_Search_Handler`, `WP_REST_Term_Search_Handler`,
`WP_REST_Post_Format_Search_Handler`) требуют живых цепочек запросов постов и
терминов, а `WP_REST_Search_Controller` уже покрыт отклонением каталога
`wp-includes/rest-api/endpoints/` в `config/not-suitable-files.md`.
Возвращаться к нему стоит только если REST-поиск станет намеренно поддерживаемой
подсистемой.

Связанные документы:
- [symbol-eligibility.md](symbol-eligibility.md) — правила пригодности символов
- [runtime.md](runtime.md) — ограничения рантайма
- [config.md](config.md) — модель конфигурации
- [parser.md](parser.md) — механизмы генерации
- [symbol-eligibility-discussion.md](symbol-eligibility-discussion.md) —
  открытый вопрос «REST Object and Dispatch Boundary», на который отвечает этот документ


## Текущее состояние

После первоначального анализа в проекте появился полноценный механизм
runtime-adapted классов (коммиты `620bd1a`, `85f111d`, `8d2a4c2`):

- `config/instance-methods.php` выбирает безопасные instance-методы исходного
  класса WordPress;
- `Instance_Methods_Trait_Copier` переносит их без изменения в генерируемый
  трейт пространства имён `Unitest_WP_Copy`;
- ручной класс в `wp-runtime/custom-mocks/*` подключает трейт, предоставляет
  нужные свойства/константы и вручную адаптирует несовместимые методы;
- `Runtime_Classes_Doc_Builder` автоматически публикует такой класс в первой
  секции `SYMBOLS-INFO.md`: методы трейта помечаются `[wp]`, ручные методы —
  `[adapted]`, отдельно перечисляются публичные свойства;
- сам сгенерированный трейт остаётся внутренней деталью и наружу как символ не
  документируется.

Рабочий прецедент — `\Unitest_WP_Copy\WPDB_Runtime`: семь методов `wpdb`
переносятся через `wpdb__Copied_Methods`, `_real_escape()` адаптирован вручную,
а публичная поверхность класса генерируется в `SYMBOLS-INFO.md`. Следовательно,
для REST API частично переносимый класс теперь является штатным механизмом
проекта, а не отдельным экспериментальным решением. Это существенно меняет
оценку вариантов для `WP_REST_Server` ниже.

Уже доступно (`SYMBOLS-INFO.md`):

- `WP_HTTP_Response{}`, `WP_REST_Response{}`, `WP_Error{}`
- вся цепочка `rest_validate_*` / `rest_sanitize_*` и ключевых слов схемы
- `rest_url()`, `rest_get_url_prefix()`, `get_rest_url()` (кастомный мок),
  `rest_filter_response_by_context()`, `rest_is_field_included()`,
  `rest_parse_embed_param()`, `register_rest_field()`
- все общие хелперы, нужные ядру REST: `apply_filters()`, `do_action()`,
  `add_filter()`, `did_action()`, `_doing_it_wrong()`, `__()`, `wp_json_encode()`,
  `wp_parse_args()`, `wp_parse_list()`, `wp_parse_str()`, `wp_parse_url()`,
  `wp_list_filter()`, `wp_is_numeric_array()`, `wp_is_json_media_type()`,
  `wp_unslash()`, `wp_check_jsonp_callback()`, `wp_get_nocache_headers()`,
  `trailingslashit()`, `untrailingslashit()`, `absint()`, `is_wp_error()`,
  `sanitize_url()`, `sanitize_title()`, `home_url()`, `get_post_types()`,
  `__return_true()`, `get_option()` (кастомный мок)

Отсутствует / отклонено сейчас: `WP_REST_Request`, `WP_REST_Server`,
`WP_REST_Controller`, `register_rest_route()`, `rest_get_server()`,
`rest_do_request()`, `rest_ensure_request()`, `rest_ensure_response()`,
`rest_convert_error_to_response()`, `rest_get_endpoint_args_for_schema()`,
`rest_authorization_required_code()`, `rest_send_allow_header()`,
`rest_filter_response_fields()`, `rest_handle_options_request()`.


## Инвентаризация зависимостей (по исходникам WP 6.7.5 / 7.0)

### `WP_REST_Request` — `wp-includes/rest-api/class-wp-rest-request.php` (1080 строк)

Полностью работающий в памяти объект-значение, `implements ArrayAccess`.
Ни глобальных переменных, ни суперглобалов, ни вывода, ни БД.

| Блокер | Где | Вес |
| --- | --- | --- |
| константа `WP_Http::BAD_REQUEST` | `parse_json_params()` | тривиальный |
| `get_option( 'permalink_structure' )` | `from_url()` | тривиальный |
| `rest_convert_error_to_response()` | `sanitize_params()`, `has_valid_params()` | копируется уже сейчас |

`rest_convert_error_to_response()` был отклонён только потому, что не был
скопирован `WP_REST_Response`; эта причина устарела.

### `WP_REST_Response` / `WP_HTTP_Response`

Уже скопированы. Дополнительных работ не требуется.

### `WP_REST_Server` — `wp-includes/rest-api/class-wp-rest-server.php` (1987 строк)

Тяжёлая связанность локализована в узком месте. Полная классификация методов:

**Ядро, работающее в памяти (блокеров сверх уже доступных символов нет):**

| Метод | Примечание |
| --- | --- |
| `__construct` | собирает мета-эндпоинты `/` и `/batch/v1` |
| `register_route` | чистая запись в реестр |
| `get_routes` | `apply_filters`, `wp_list_filter`, `wp_parse_args` |
| `get_namespaces` | чистый |
| `get_route_options` | чистый |
| `dispatch` | `apply_filters`, `rest_ensure_response` |
| `is_dispatching` | чистый |
| `match_request_to_handler` | `trailingslashit` |
| `respond_to_request` | `apply_filters`, `rest_authorization_required_code` |
| `response_to_data` | `wp_is_numeric_array` |
| `get_response_links` | чистый |
| `get_compact_response_links` | чистый |
| `embed_links` | `WP_REST_Request::from_url` |
| `envelope_response` | `apply_filters` |
| `check_authentication` | только `apply_filters( 'rest_authentication_errors' )` |
| `error_to_response` | `rest_convert_error_to_response` |
| `json_error` | `wp_json_encode` (+ `set_status`) |
| `get_json_encode_options` | `apply_filters` |
| `get_json_last_error` | чистый |
| `get_headers` | чистое преобразование массива вида `$_SERVER` (массив приходит аргументом) |
| `get_namespace_index` | `rest_url`, `rest_ensure_response` |
| `get_data_for_routes` / `get_data_for_route` | `rest_url`, ключевые слова схемы |
| `get_max_batch_size` | `apply_filters` |
| `serve_batch_request_v1` | нужна `WP_Http::MULTI_STATUS` |
| `get_target_hints_for_link` | `WP_REST_Request::from_url`, `rest_send_allow_header` |

**Обогащение индекса (нужны небольшие моки):**

| Метод | Отсутствующая зависимость |
| --- | --- |
| `get_index` | опции `page_for_posts`, `page_on_front`, `show_on_front` |
| `add_active_theme_link_to_index` | `current_user_can()`, `wp_get_theme()` |
| `add_site_logo_to_index` | `get_theme_mod()` |
| `add_site_icon_to_index` | опция `site_icon`, `get_site_icon_url()` |
| `add_image_to_index` | `rest_get_route_for_post()` (рантайм типов записей) |

**Граница живого HTTP (в этом рантайме принципиально неприменимо):**

| Метод | Связанность |
| --- | --- |
| `serve_request` | `$_GET`, `$_POST`, `$_FILES`, `$_SERVER`, `echo`, `$current_user`/`WP_User`, `is_user_logged_in()` |
| `set_status` | `status_header()` |
| `send_header` / `send_headers` | `header()` |
| `remove_header` | `header_remove()` |
| `get_raw_data` | `file_get_contents( 'php://input' )`, `$HTTP_RAW_POST_DATA` |

Это **6 методов**: пять непосредственных точек HTTP-границы плюс
`send_headers()`, который безопасно становится in-memory после адаптации
вызываемого им `send_header()`. Всё, что нужно для регистрации и
диспетчеризации, работает в памяти. Точные количества методов и строк намеренно
не фиксируются: они меняются между поддерживаемыми линиями WordPress и должны
повторно проверяться при конфигурировании каждой линии.

### `WP_REST_Controller` — `endpoints/class-wp-rest-controller.php` (681 строка)

Абстрактный базовый класс. Требует `rest_get_server()`,
`rest_get_endpoint_args_for_schema()`, `rest_filter_response_by_context()`
(доступна), `rest_is_field_included()` (доступна), `sanitize_title()`
(доступна) и глобальную `$wp_rest_additional_fields` (уже используется
скопированной `register_rest_field()`). Ни БД, ни вывода.

### Функции `rest-api.php`, нужные в целевой области

| Функция | Блокер |
| --- | --- |
| `register_rest_route` | только `rest_get_server()` |
| `rest_get_server` | класс `WP_REST_Server` + глобальная `$wp_rest_server` |
| `rest_do_request` | `rest_ensure_request` + `rest_get_server` |
| `rest_ensure_request` | класс `WP_REST_Request` |
| `rest_ensure_response` | нет (все зависимости доступны) |
| `rest_convert_error_to_response` | нет (все зависимости доступны) |
| `rest_get_endpoint_args_for_schema` | константа `WP_REST_Server::CREATABLE` |
| `rest_send_allow_header` | нет |
| `rest_filter_response_fields` | нет |
| `rest_handle_options_request` | нет |
| `rest_authorization_required_code` | `is_user_logged_in()` |

Вне области, остаются отклонёнными: `rest_api_init`, `rest_api_register_rewrites`,
`rest_api_default_filters`, `create_initial_rest_routes`, `rest_api_loaded`,
`rest_send_cors_headers`, все функции кук и паролей приложений,
`rest_get_route_for_post/term/...`, `rest_preload_api_request`.

### Общие блокеры для всех вариантов

1. **Константы статусов `WP_Http`** — `WP_Http::BAD_REQUEST`,
   `WP_Http::MULTI_STATUS`. Копировать класс `WP_Http` целиком нельзя: он тянет
   живой транспорт `request()`/`get()`/`post()`. Два чистых варианта:
   - шим-класс `WP_Http` только с константами в
     `wp-runtime/custom-mocks/wp-includes/` (под защитой `class_exists`), либо
   - правило в парсерном `Source_Code_Replacer` для констант класса, по аналогии
     с существующей заменой статических методов.   [Я думаю, вот этот вариант предпочтительнее.]

2. **`is_user_logged_in()`** — нужна для `rest_authorization_required_code()`.
   Идеальный кандидат на `mockable`, но оригинальное тело —
   `wp_get_current_user()->exists()`, чего в рантайме нет, поэтому это должен
   быть **кастомный мок** с дефолтом `false` и швом для переопределения через
   `WP_Mock`. [да нужно согласен, кастомный мок предпочтительнее]
3. **`current_user_can()`** — достижима только из обогащения индекса. Обработка
   та же (кастомный мок, дефолт `false`, переопределение через `WP_Mock`), либо
   отказаться и принять, что обогащение ссылок в `get_index()` не тестируется. 
    [да согласен, кастомный мок предпочтительнее]

4. **Опции, которые надо добавить в `$GLOBALS['stub_wp_options']`**:
   `permalink_structure => '/%postname%/'` (запрошено явно: ЧПУ — реалистичное
   значение по умолчанию, и `WP_REST_Request::from_url()` от него зависит),
   плюс `show_on_front => 'posts'`, `page_on_front => 0`, `page_for_posts => 0`,
   `site_icon => 0`. 
    [да согласен, нужно добавить в глобальный массив опций]

5. **Сброс состояния между тестами** — сервер REST хранится в глобальной
   переменной процесса. Обязателен документированный рецепт для `tearDown()`
   (`unset( $GLOBALS['wp_rest_server'] )`) или небольшой тестовый хелпер, иначе
   зарегистрированные маршруты протекают между тестами. 
    [да согласен, нужно добавить в документацию]



## Вариант 1 — Дословная копия + рантайм-подкласс сервера

**Идея.** Копируем `WP_REST_Server`, `WP_REST_Request` и `WP_REST_Controller`
дословно через парсер. 6 методов живого HTTP нейтрализуем **наследованием**, а не
правкой сгенерированного кода — добавляем ручной адаптер:

```php
// wp-runtime/custom-mocks/wp-includes/class-rest-server-runtime.php
namespace Unitest_WP_Copy;

class REST_Server_Runtime extends \WP_REST_Server {

	/** Записывается вместо отправки клиенту. */
	public array $sent_headers = [];
	public ?int  $sent_status  = null;

	public function set_status( $code )            { $this->sent_status = $code; }
	public function send_header( $key, $value )    { $this->sent_headers[ $key ] = $value; }
	public function remove_header( $key )          { unset( $this->sent_headers[ $key ] ); }
	public static function get_raw_data()          { return ''; }
	public function serve_request( $path = null )  { throw new \LogicException( 'Live REST serving is out of runtime scope; use rest_do_request().' ); }
}
```

и подключаем через init-part:

```php
// wp-runtime/init-parts/wp-includes/rest-api.php
add_filter( 'wp_rest_server_class', static fn() => \Unitest_WP_Copy\REST_Server_Runtime::class );
```

`rest_get_server()` и так создаёт объект через этот фильтр, поэтому скопированный
код не меняется вообще. Все методы, кроме шести переопределённых, — оригинальный
код WordPress.

Дополнительно нужны: шим констант `WP_Http`, кастомные моки `is_user_logged_in()`
и `current_user_can()`, пять значений опций и записи в конфиге из инвентаризации.

**Плюсы**
- Максимальная достоверность: регистрация, сопоставление, диспетчеризация,
  валидация, санитизация, ссылки, встраивание, конверт и batch-ответы —
  неизменённый ядровой код.
- Граница HTTP не просто «не поддерживается», а *превращена в проверяемую запись
  в памяти*, поэтому тесты могут проверять статус и заголовки, которые выдал бы
  реальный эндпоинт.
- `serve_request()` громко падает с понятным сообщением вместо вывода в поток.
- Никакой поштучной конфигурации методов, но при обновлении WordPress всё равно
  нужно повторно проверять зависимости всего скопированного класса, а не только
  сигнатуры адаптированной HTTP-границы.
- Используются только те механизмы, что уже есть в проекте (`custom-mocks`,
  `init-parts`, ядровой фильтр `wp_rest_server_class`).

**Минусы**
- Класс копируется целиком, поэтому `add_image_to_index()` всё ещё ссылается на
  `rest_get_route_for_post()`. Либо задать `current_user_can()` дефолт `false`
  (что коротит `add_active_theme_link_to_index()` и не даёт дойти до
  `add_image_to_index()`), либо переопределить в адаптере и все четыре метода
  обогащения индекса.
- Слегка нарушает правило пригодности «каждая зависимость должна быть
  разрешима»: несколько зависимостей разрешимы только на путях, которые
  блокирует адаптер. Это нужно явно зафиксировать комментарием в конфиге.
- Не использует появившийся после первоначального анализа штатный механизм
  частичного копирования классов. В `SYMBOLS-INFO.md` будут одновременно видны
  полностью скопированный `WP_REST_Server` и runtime-подкласс, хотя фактически
  поддерживается только поверхность подкласса; это делает публичную границу
  менее ясной.
- `Runtime_Classes_Doc_Builder` не анализирует методы родительского класса:
  у runtime-подкласса в первой секции `SYMBOLS-INFO.md` будут видны только
  переопределения `[adapted]`, но не унаследованные оригинальные методы `[wp]`.

**Какую поверхность тестов это открывает**

```php
register_rest_route( 'my/v1', '/items/(?P<id>\d+)', [
	'methods'             => 'GET',
	'callback'            => fn( $request ) => rest_ensure_response( [ 'id' => (int) $request['id'] ] ),
	'permission_callback' => '__return_true',
	'args'                => [ 'id' => [ 'type' => 'integer' ] ],
] );

$response = rest_do_request( new WP_REST_Request( 'GET', '/my/v1/items/42' ) );

$this->assertSame( 200, $response->get_status() );
$this->assertSame( [ 'id' => 42 ], $response->get_data() );
```


## Вариант 2 — Трейт instance-методов + runtime-adapted сервер (РЕКОМЕНДУЕТСЯ)

**Идея.** Точно повторить существующий паттерн `wpdb` / `WPDB_Runtime`.
Сконфигурировать работающие в памяти методы в `config/instance-methods.php` как
`WP_REST_Server__Copied_Methods`, затем написать
`\Unitest_WP_Copy\REST_Server_Runtime` в `wp-runtime/custom-mocks/`. Класс
подключает трейт, объявляет константы (`READABLE`, `CREATABLE`, `EDITABLE`,
`DELETABLE`, `ALLMETHODS`), предоставляет состояние, которое читают скопированные
методы, и вручную реализует HTTP-границу. Конструктор включается в трейт вместе
со связанной группой методов, если пройдёт проверку, иначе адаптируется вручную.

Глобальное имя `WP_REST_Server` всё равно обязательно: его константы стоят в
default-аргументах `rest_get_endpoint_args_for_schema()` и
`WP_REST_Controller`, а `rest_api_loaded()` содержит `instanceof
WP_REST_Server`. Совместимость следует дать через `class_alias()` на
runtime-adapted класс (либо через тонкий глобальный класс-наследник) при
bootstrap до загрузки зависимых функций/классов. В таком случае и исходный API
WordPress сохраняет глобальное имя, и реальная поддерживаемая поверхность
остаётся одной, автоматически документируемой в `SYMBOLS-INFO.md`.

**Плюсы**
- Строго соответствует `symbol-eligibility.md`: ни один неприменимый метод не
  попадает в рантайм.
- Класс рантайма содержит ровно поддерживаемый контракт, мёртвых путей нет.
- Прецедент уже есть, покрыт тестами и описан в `runtime.md`, `parser.md` и
  `config.md`.
- `Runtime_Classes_Doc_Builder` автоматически сделает класс видимым
  пользователям: публичные неизменённые REST-методы получат `[wp]`, методы
  HTTP-границы и другие ручные методы — `[adapted]`, наблюдаемое состояние
  заголовков/статуса попадёт в список публичных свойств. Protected/private
  методы трейта в публичную секцию не выводятся.
- При добавлении новых методов не требуется вручную поддерживать отдельный
  раздел документации символов: `make parser.run` регенерирует контракт.

**Минусы**
- Конфиг содержит большой список методов и их версии `since`; при обновлении
  каждой линии WP нужно повторно проверять сигнатуры, обращения через `$this`,
  `self::`/`parent::` и связность выбранной группы.
- `__construct` нельзя включать изолированно: он образует связанную группу с
  `register_route()` и `get_max_batch_size()`. Его можно оставить оригинальным
  методом трейта, если вся группа и используемое состояние проходят проверку
  пригодности; ручная адаптация нужна только если эта проверка выявит
  несовместимый путь.
- Адаптер обязан предоставить все внутренние свойства, к которым обращаются
  методы трейта; несовпадение проявится только при исполнении соответствующего
  пути, поэтому тесты должны покрывать каждый включённый публичный метод.
- Алиас/глобальный совместимый класс и порядок bootstrap требуют отдельного
  parser-теста: до объявления функций с default-аргументами глобальный
  `WP_REST_Server` уже должен существовать.

### Требования к публичному контракту адаптера

Чтобы новый класс следовал уже реализованной модели `WPDB_Runtime`:

1. Сам адаптер должен находиться в пространстве имён `Unitest_WP_Copy`, иначе
   текущий `Runtime_Classes_Doc_Builder` пропустит файл.
2. У класса или файла нужен однострочный docblock summary — он попадёт в
   `SYMBOLS-INFO.md`.
3. Оригинальные безопасные методы должны приходить только из generated trait;
   изменённые методы объявляются в адаптере и получают `[adapted]`.
4. Публичными следует сделать только наблюдаемые тестами данные (например,
   записанные заголовки и статус); служебное состояние сервера сохраняется с
   исходной видимостью, насколько это допускают методы трейта.
5. Тестировать надо адаптер как публичный класс в `tests/custom-mocks/...`, а
   генерацию/маркировку методов — в `tests/parser/...`.


## Вариант 3 — Дословная копия + глобальные стабы функций, без адаптера

**Идея.** Скопировать три класса и функции `rest-api.php` дословно, добавить
недостающие глобальные функции кастомными моками (`is_user_logged_in()`,
`current_user_can()`, `status_header()`, `wp_get_theme()`, `get_theme_mod()`,
`get_site_icon_url()`) и задокументировать `serve_request()` как «вызывать нельзя».

**Плюсы**
- Минимум нового кода рантайма: только конфиг и несколько моков.
- Всё остаётся нетронутым ядровым кодом.

**Минусы**
- `serve_request()`, `send_header()` и `set_status()` остаются достижимыми и при
  попадании в них теста выполнят настоящие `header()` / `echo` внутри PHPUnit,
  давая шум «headers already sent» и опасное загрязнение тестов.
- Требует моков `wp_get_theme()` и `get_theme_mod()`, затаскивая в проект
  понятия рантайма тем без реальной пользы.
- Нет шва для проверки статуса и заголовков в памяти, поэтому самая интересная
  часть поведения эндпоинтов остаётся ненаблюдаемой.


## Вариант 4 — Только слой запроса и схем, без сервера

**Идея.** Скопировать только `WP_REST_Request`, `rest_ensure_request()`,
`rest_ensure_response()`, `rest_convert_error_to_response()`,
`rest_get_endpoint_args_for_schema()` и шим констант `WP_Http`. Сервер,
маршрутизацию и диспетчеризацию оставить отклонёнными.

**Плюсы**
- Полностью соответствует текущим правилам пригодности, границы не нарушаются.
- Очень маленький диф и почти нулевое сопровождение.
- Сразу полезно: покрывает валидацию аргументов по схеме, санитизацию,
  фильтрацию по контексту и преобразование ошибок в ответ — а именно там живёт
  большая часть реальных REST-багов в плагинах.

**Минусы**
- Не решает поставленную задачу: нет `register_rest_route()`, нет
  диспетчеризации, нет тестирования контроллеров.
- `rest_get_endpoint_args_for_schema()` всё равно требует
  `WP_REST_Server::CREATABLE`, то есть шим-класс `WP_REST_Server` с константами
  понадобится в любом случае — что само по себе аргумент идти дальше.

Лучше рассматривать как **фазу 1** Варианта 1, а не как итоговый ответ.



## Рекомендация

**Вариант 2: generated instance-method trait + документируемый
runtime-adapted класс**, по фазам. После появления
`Runtime_Classes_Doc_Builder` это лучше соответствует и правилам пригодности, и
публичной модели проекта, чем полная копия `WP_REST_Server` с подклассом.

1. Фаза 1 (границы не нарушаются): шим констант `WP_Http`, `WP_REST_Request`,
   `rest_convert_error_to_response()`, `rest_ensure_request()`,
   `rest_ensure_response()`, значения опций по умолчанию, включая
   `permalink_structure`.
2. Фаза 2: полный повторный аудит связной группы методов `WP_REST_Server` для
   каждой поддерживаемой WP-линии; конфиг `instance-methods.php`; регенерация
   `WP_REST_Server__Copied_Methods`; namespaced
   `\Unitest_WP_Copy\REST_Server_Runtime` с ручной HTTP-границей; глобальная
   совместимость имени `WP_REST_Server` и выбор адаптера в `rest_get_server()`;
   кастомные моки `is_user_logged_in()` / `current_user_can()` (дефолт `false`);
   `register_rest_route()`, `rest_get_server()`, `rest_do_request()`,
   `rest_send_allow_header()`, `rest_filter_response_fields()`,
   `rest_handle_options_request()`, `rest_authorization_required_code()`,
   `rest_get_endpoint_args_for_schema()`.
3. Фаза 3: `WP_REST_Controller` плюс документированный рецепт сброса
   `$GLOBALS['wp_rest_server']` в тестах.

Каждая фаза самостоятельно поставляема и тестируема. Во второй фазе порядок
работ обязателен: сначала config, затем `make parser.run`, затем ручной адаптер и
тесты через него, затем `make phpunit`. Генерируемый трейт вручную не меняется и
не считается публичным символом; ожидаемый внешний контракт проверяется по
сгенерированной секции `REST_Server_Runtime` в `SYMBOLS-INFO.md`.


## Открытые вопросы к мейнтейнеру

1. Должны ли `current_user_can()` / `is_user_logged_in()` стать постоянными
   кастомными моками рантайма (дефолт `false`, переопределение через `WP_Mock`),
   или REST-область должна вовсе их избегать за счёт переопределения обогащения
   индекса в адаптере? [да, кастомные моки предпочтительнее]

2. Константы `WP_Http` — шим-класс в custom-mocks, или парсер должен получить
   замену констант класса (`WP_Http::BAD_REQUEST` -> литерал)? [Общие блокеры для всех вариантов тут ответил]

3. Как дать глобальную совместимость `WP_REST_Server`: `class_alias()` на
   публичный namespaced адаптер или тонкий глобальный класс-наследник? Сам
   адаптер уже не следует считать внутренним: текущий контракт runtime-adapted
   классов в `SYMBOLS-INFO.md` прямо предназначает их для использования и
   расширения в тестах.

4. Нужен ли `wp_rest_server_class` filter, если выбран `class_alias()`, или
   достаточно стандартного литерального имени в `rest_get_server()`?

5. Допустим ли отдельный базовый класс или трейт в `tests/` для сброса
   REST-состояния, учитывая, что `tests.md` сейчас предполагает обычный
   `PHPUnit\Framework\TestCase`?
