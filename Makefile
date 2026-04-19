
define php_run
	docker run --rm $(1) --name UNITEST_WP_COPY__php --user 1000:1000 \
		-v "$(CURDIR):/app"  -w /app \
		composer sh -c "$2"
endef

php.connect:
	$(call php_run, -it, sh)

composer:
	$(call php_run, composer  $(filter-out $@,$(MAKECMDGOALS)))
composer.install:
	$(call php_run, , composer install  $(filter-out $@,$(MAKECMDGOALS)))
composer.update:
	$(call php_run, , composer update  $(filter-out $@,$(MAKECMDGOALS)))

phpunit:
	$(call php_run, , composer run phpunit -- --colors=always)

run.parser:
	$(call php_run, , php _parser/run.php)

# make release WP_LINE=6.8
release:
	WP_LINE="$(WP_LINE)" bash _releaser/release.sh
