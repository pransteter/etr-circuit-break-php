Makefile.PHONY: help

help:
	@grep -E '^[a-zA-Z-]+:.*?## .*$$' Makefile | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "[32m%-15s[0m %s\n", $$1, $$2}'

update-composer: ## Update composer dependencies
	docker compose exec php composer update

run-tests: ## Run tests
	docker compose exec php ./vendor/bin/phpunit ./tests 

up: ## Run docker compose
	docker compose down && docker compose build --no-cache && docker compose up -d --remove-orphans

down: ## Stop docker compose
	docker compose down -v

sh: ## Access container sh
	docker compose exec php sh
