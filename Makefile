
define php_run
	docker run --rm $(1) --name UNITEST_WP_COPY__php --user 1000:1000 \
		-v "$(CURDIR):/app"  -w /app \
		composer sh -c "$2"
endef

php.connect:
	$(call php_run, -it, sh)

composer:
	$(call php_run,, composer  $(filter-out $@,$(MAKECMDGOALS)))
composer.install:
	$(call php_run,, composer install  $(filter-out $@,$(MAKECMDGOALS)))
composer.update:
	$(call php_run,, composer update  $(filter-out $@,$(MAKECMDGOALS)))

# $ make phpunit WP_LINE=6.8
phpunit:
	$(call php_run, -e WP_LINE="$(WP_LINE)", composer run phpunit -- --colors=always)

parser.run:
	$(call php_run, , php _parser/run.php)

# make release WP_LINE=6.8
release:
	WP_LINE="$(WP_LINE)" bash _releaser/release.sh
release.all:
	@status=0; \
	for wp_line in 6.5 6.6 6.7 6.8 6.9; do \
		echo "== release $$wp_line =="; \
		$(MAKE) release WP_LINE="$$wp_line" || status=1; \
		echo; \
	done; \
	exit $$status

# make worktrees.run cmd="git status --short"
WORKTREE_DIRS := $(sort $(wildcard worktrees/wp-*))
worktrees.run:
	@if [ -z "$(cmd)" ]; then \
		echo 'Use: make worktrees-run cmd="git status --short"'; \
		exit 1; \
	fi
	@for dir in $(WORKTREE_DIRS); do \
		echo "== $$dir =="; \
		sh -c 'cd "$$1" && $(cmd)' -- "$$dir"; \
		echo; \
	done
