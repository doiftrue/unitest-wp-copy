
define php_run
	docker run --rm -it --name UNITEST_WP_COPY__php --user 1000:1000 \
		-v "$(CURDIR):/app"  -w /app \
		composer sh -c "$1"
endef

php.connect:
	$(call php_run, sh)

composer.install:
	$(call php_run, composer install)
composer.update:
	$(call php_run, composer update)

phpunit:
	$(call php_run, composer run phpunit)

run.parser:
	$(call php_run, php _parser/run.php)
