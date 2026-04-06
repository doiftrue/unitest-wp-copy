
define php_run
	docker run --rm $(1) --name UNITEST_WP_COPY__php --user 1000:1000 \
		-v "$(CURDIR):/app"  -w /app \
		composer sh -c "$2"
endef

php.connect:
	$(call php_run, -it, sh)

composer.install:
	$(call php_run, , composer install)
composer.update:
	$(call php_run, , composer update)

phpunit:
	$(call php_run, , composer run phpunit -- --colors=always)

run.parser:
	$(call php_run, , php _parser/run.php)
