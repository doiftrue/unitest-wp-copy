
define php_run
	docker run --rm $(1) --name UNITEST_WP_COPY__php --user 1000:1000 \
		-v "$(CURDIR):/app"  -w /app \
		composer sh -c "$2"
endef

php.connect:
	$(call php_run, -it, sh)

composer:
	$(call php_run,, composer  $(filter-out $@,$(MAKECMDGOALS)))
composer_install:
	$(call php_run,, composer install  $(filter-out $@,$(MAKECMDGOALS)))
composer_update:
	$(call php_run,, composer update  $(filter-out $@,$(MAKECMDGOALS)))

# $ make phpunit WP_LINE=6.8
phpunit:
	$(call php_run, -e WP_LINE="$(WP_LINE)", composer run phpunit -- --colors=always)

parser_run:
	$(call php_run, , php parser/run.php)


# make switch WP_LINE=6.8
switch:
	@[ -n "$(WP_LINE)" ] || { echo 'Use: make switch WP_LINE=6.8'; exit 1; }
	$(call php_run,, composer require --dev wordpress/wordpress:$(WP_LINE).* --no-interaction --with-dependencies)
	$(MAKE) parser_run

# make release WP_LINE=6.8
# make release WP_LINE=6.8 NOT_PUSH=1
release:
	WP_LINE="$(WP_LINE)" NOT_PUSH="$(NOT_PUSH)" bash releaser/release.sh

WP_LINES := $(notdir $(patsubst %/,%,$(wildcard wp-runtime/wp-line-extra/*/)))
release_all:
	@status=0; \
	for wp_line in $(WP_LINES); do \
		echo "== release $$wp_line =="; \
		$(MAKE) release WP_LINE="$$wp_line" || status=1; \
		echo; \
	done; \
	exit $$status


# make worktrees_run cmd="git status --short"
WORKTREE_DIRS := $(sort $(wildcard worktrees/wp-*))
worktrees_run:
	@if [ -z "$(cmd)" ]; then \
		echo 'Use: make worktrees-run cmd="git status --short"'; \
		exit 1; \
	fi
	@for dir in $(WORKTREE_DIRS); do \
		echo "== $$dir =="; \
		sh -c 'cd "$$1" && $(cmd)' -- "$$dir"; \
		echo; \
	done

worktrees_git_status:
	@for dir in $(WORKTREE_DIRS); do \
		echo "== $$dir =="; \
		sh -c 'cd "$$1" && git status --short' -- "$$dir"; \
		echo; \
	done

# $ make php.run code='include "wp-core/wp-includes/version.php"; echo $wp_version, "\n";'
php.run:
	@if [ -z "$(strip $(value code))" ]; then \
		echo 'Use: make php.run code='\''include "wp-core/wp-includes/version.php"; echo $$wp_version, "\\n";'\'''; \
		exit 1; \
	fi
	$(file >tmp/.phprun.php,<?php)
	$(file >>tmp/.phprun.php,$(value code))
	@status=0; \
	$(call php_run, , php tmp/.phprun.php) || status=$$?; \
	rm -f tmp/.phprun.php; \
	exit $$status
