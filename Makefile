.PHONY: help
help:
	@echo 'Following targets exist:'
	@echo ''
	@echo '  cache-clear - clear the symfony cache'
	@echo '  composer-install - install the specified vendor packages'
	@echo '  composer-update - update the vendor packages'
	@echo '  tests - run the tests via PHPUnit'
	@echo '  validate - check the entities match the database schema'
	@echo ''

.PHONY: cache-clear
cache-clear:
	docker compose exec php bash -c "XDEBUG_MODE=off./bin/console cache:clear"

.PHONY: composer-install
composer-install:
	docker compose exec php bash -c "XDEBUG_MODE=off composer install"

.PHONY: composer-update
composer-update:
	docker compose exec php bash -c "XDEBUG_MODE=off composer update"

.PHONY: phpstan
phpstan:
	docker compose exec php bash -c "./vendor/bin/phpstan"

.PHONY: tests
tests:
	docker compose exec php bash -c "./vendor/bin/phpunit"

.PHONY: validate
validate:
	docker compose run --rm php bash -c "XDEBUG_MODE=off ./bin/console doctrine:schema:validate"
