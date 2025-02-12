Makefile.PHONY: help

help:
	@grep -E '^[a-zA-Z-]+:.*?## .*$$' Makefile | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "[32m%-15s[0m %s\n", $$1, $$2}'

update-composer: ## Update composer dependencies
	docker compose run php composer update

run-tests: ## Run tests
	docker compose run php ./vendor/bin/phpunit ./tests
