.PHONY: help
help:
	@echo 'Following targets exist:'
	@echo ''
	@echo '  cache-clear - clear the symfony cache'
	@echo '  composer-install - install the specified vendor packages'
	@echo '  composer-update - update the vendor packages'
	@echo '  start - starts up the docker docker containers'
	@echo '  stop - stop up the docker docker containers'
	@echo '  composer-update - update the vendor packages'
	@echo '  tests - run the tests via PHPUnit'
	@echo '  validate - check the entities match the database schema'
	@echo '  watch - update for HTML/CSS changes and refresh'
	@echo ''

.PHONY: cache-clear
cache-clear:
	docker compose exec php bash -c "/bin/console cache:clear"

.PHONY: composer-install
composer-install:
	docker compose exec php bash -c "composer install"

.PHONY: composer-update
composer-update:
	docker compose exec php bash -c "composer update"

.PHONY: phpstan
phpstan:
	docker compose exec php bash -c "./vendor/bin/phpstan"

.PHONY: start
start:
	docker compose up -d

.PHONY: stop
stop:
	docker compose down

.PHONY: tests
tests:
	docker compose exec php bash -c "./vendor/bin/phpunit"

.PHONY: validate
validate:
	docker compose exec php bash -c "./bin/console doctrine:schema:validate"

.PHONY: watch
watch:
	docker compose exec php bash -c "./bin/console tailwind:build --watch"
