
define php_run
	docker run --rm -it --name UNITEST_WP_COPY__php --user 1000:1000 \
		-v "$(CURDIR):/app"  -w /app \
		php:8.4-cli sh -c "$1"
endef

php.connect:
	$(call php_run, sh)

run.parser:
	$(call php_run, php _parser/run.php)
