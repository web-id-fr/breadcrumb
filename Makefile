#!/usr/bin/make
.PHONY: help

DOCKER_COMPOSE ?= docker compose
DOCKER_EXEC_PHP=${DOCKER_COMPOSE} exec php-cli

help:
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

install: build start composer_install ## Setup dev docker compose stack
	@echo "🔨 Project setup done!"

build: ## Build dev docker compose stack
	@echo "🐳 Build or rebuild stack images..."
	@WWWUSER=$(shell id -u) WWWGROUP=$(shell id -g) docker compose --profile "*" build

start: ## Start stack
	@echo "🐳 Make sure docker stack is up..."
	$(DOCKER_COMPOSE) up -d --wait

stop: ## Stop stack
	$(DOCKER_COMPOSE) --profile "*" stop

down: ## Down stack
	$(DOCKER_COMPOSE) --profile "*" down

restart: stop start

destroy: ## Destroy docker compose stack with volumes
	$(DOCKER_COMPOSE) --profile "*" down -v

composer_install: # Install composer dependencies
	@echo "🔨 Setup Composer dependencies..."
	@$(DOCKER_EXEC_PHP) composer install

ci: cs stan test ## Run CI suite

cs: ## Run Pint with fixing
	$(DOCKER_EXEC_PHP) ./vendor/bin/pint

stan: ## Run PHPStan
	$(DOCKER_EXEC_PHP) ./vendor/bin/phpstan --memory-limit=1G

test: ## Run PHPUnit tests
	$(DOCKER_EXEC_PHP) ./vendor/bin/phpunit
