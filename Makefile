-include Makefile-extend.mk

# Handle `$ make` run without target
.DEFAULT_GOAL := h
h: ## Display all available targets with description
	@grep -h -E '^[a-zA-Z0-9._-]+:.*?## .*$$' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'


define php_run
	@mkdir -p "$(CURDIR)/tmp/composer-cache"
	docker run --rm $(1) --name UNITEST_WP_COPY__php --user 1000:1000 \
		-v "$(CURDIR):/app"  -w /app \
		-v "$(CURDIR)/tmp/composer-cache:/tmp/composer-cache" \
		-e COMPOSER_CACHE_DIR=/tmp/composer-cache \
		composer sh -c "$(2)"
endef

php.connect: ## Open PHP container
	$(call php_run, -it, sh)

composer: ## Run Composer. Eg: make composer update vendor/package
	$(call php_run,, composer  $(filter-out $@,$(MAKECMDGOALS)))
composer.install: ## Install dependencies
	$(call php_run,, composer install  $(filter-out $@,$(MAKECMDGOALS)))
composer.update: ## Update dependencies
	$(call php_run,, composer update  $(filter-out $@,$(MAKECMDGOALS)))

phpunit: ## Run tests. Optional: make phpunit WP_LINE=6.8
	$(call php_run, -e WP_LINE="$(WP_LINE)", composer run phpunit -- --colors=always)

parser.run: ## Generate WP copies
	$(call php_run, , php parser/run.php --verbose)

switch: ## Switch WP version. Eg: make switch  WP_LINE=6.8
	@[ -n "$(WP_LINE)" ] || { echo 'Use: make switch WP_LINE=6.8'; exit 1; }
	$(call php_run,, composer require --dev wordpress/wordpress:$(WP_LINE).* --no-interaction --with-dependencies)
	$(MAKE) parser.run

release: ## Release WP line. Eg: make release  WP_LINE=6.8  NOT_PUSH=1
	WP_LINE="$(WP_LINE)" NOT_PUSH="$(NOT_PUSH)" bash releaser/release.sh

WP_LINES := $(notdir $(patsubst %/,%,$(wildcard wp-runtime/wp-line-extra/*/)))
release.all: ## Release all WP lines
	@status=0; \
	for wp_line in $(WP_LINES); do \
		printf "\033[35m\n============== RELEASE $$wp_line ==============\n\n\033[0m"; \
		$(MAKE) release WP_LINE="$$wp_line" || status=1; \
		echo; \
	done; \
	exit $$status


WORKTREE_DIRS := $(sort $(wildcard worktrees/wp-*))
worktrees.run: ## Run cmd in worktrees. Eg: make worktrees.run cmd="git status --short"
	@if [ -z "$(cmd)" ]; then \
		echo 'Use: make worktrees.run cmd="git status --short"'; \
		exit 1; \
	fi
	@for dir in $(WORKTREE_DIRS); do \
		echo "== $$dir =="; \
		sh -c 'cd "$$1" && $(cmd)' -- "$$dir"; \
		echo; \
	done

worktrees.status: ## Git status in release worktrees
	@for dir in $(WORKTREE_DIRS); do \
		echo "== $$dir =="; \
		sh -c 'cd "$$1" && git status --short' -- "$$dir"; \
		echo; \
	done

php.run: ## Run PHP code. Eg: make php.run code='echo "$wp_version\n";'
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
