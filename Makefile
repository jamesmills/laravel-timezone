THIS := $(realpath $(lastword $(MAKEFILE_LIST)))
HERE := $(shell dirname $(THIS))

.PHONY: fix lint test

fix:
	$(HERE)/vendor/bin/php-cs-fixer fix --config=$(HERE)/.php_cs

lint:
	$(HERE)/vendor/bin/php-cs-fixer fix --config=$(HERE)/.php_cs --dry-run

test: lint
