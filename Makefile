#!/usr/bin/make
.PHONY: help
.PHONY: start

DOCKER_COMPOSE ?= docker compose
DOCKER_EXEC_PHP=${DOCKER_COMPOSE} exec php-fpm

help:
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

cs: ## Run Pint with fixing
	$(DOCKER_EXEC_PHP) ./vendor/bin/pint

stan: ## Run PHPStan
	$(DOCKER_EXEC_PHP) ./vendor/bin/phpstan --memory-limit=1G

test: ## Run PHPUnit tests
	$(DOCKER_EXEC_PHP) ./vendor/bin/phpunit
