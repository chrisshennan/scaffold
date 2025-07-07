DOCKER_COMPOSE_COMMAND=docker compose run --rm php

.PHONY: help
help:
	@echo 'Following targets exist:'
	@echo ''
	@echo '  tailwind-build - build the tailwind assets'
	@echo '  tailwind-watch - update for HTML/CSS changes and refresh'
	@echo '  cache-clear - clear the symfony cache'
	@echo '  composer-install - install the specified vendor packages'
	@echo '  composer-update - update the vendor packages'
	@echo '  initialize - Install composer & tailwind dependencies in a new projct'
	@echo '  start - starts up the docker docker containers'
	@echo '  start-rebuild - starts up the docker containers after rebuilding the images'
	@echo '  stop - stop up the docker docker containers'
	@echo '  composer-update - update the vendor packages'
	@echo '  tests - run the tests via PHPUnit'
	@echo '  validate - check the entities match the database schema'
	@echo '  reset-dev-database - drop, create and migrate the development database'
	@echo ''

.PHONY: cache-clear
cache-clear:
	$(DOCKER_COMPOSE_COMMAND) bash -c "./bin/console cache:clear"

.PHONY: composer-install
composer-install:
	$(DOCKER_COMPOSE_COMMAND) bash -c "composer install"

.PHONY: composer-update
composer-update:
	$(DOCKER_COMPOSE_COMMAND) bash -c "composer update"

.PHONY: initialize
initialize: git-initialize composer-install tailwind-build start initialize-complete

.PHONY: git-initialize
git-initialize:
	@echo -n "What is your git repository origin for this new project?: "; \
	read REPO; \
	git remote remove origin; \
	git remote add origin $$REPO;

.PHONY:
initialize-complete:
	@echo ""
	@echo "##############################"
	@echo "# SCAFFOLD SETUP COMPLETE 🎉 #"
	@echo "##############################"
	@echo ""
	@echo "Your scaffold setup in now complete.  When you are ready, you can push your code to your repository via\n"
	@echo "git push origin main\n"
	@echo "To hints, tips, updates & developments, sign up to my newsletter via https://chrisshennan.com/newsletter\n"
	@echo "Happy building!"
	@echo "Chris Shennan (https://chrisshennan.com)\n"

.PHONY: php-cs-fixer-dry-run
php-cs-fixer-dry-run:
	$(DOCKER_COMPOSE_COMMAND) bash -c "./vendor/bin/php-cs-fixer fix --dry-run -v"

.PHONY: php-cs-fixer-apply
php-cs-fixer-apply:
	$(DOCKER_COMPOSE_COMMAND) bash -c "./vendor/bin/php-cs-fixer fix"

.PHONY: phpstan
phpstan:
	$(DOCKER_COMPOSE_COMMAND) bash -c "./vendor/bin/phpstan --memory-limit=1G"

.PHONY: rector-dry-run
rector-dry-run:
	$(DOCKER_COMPOSE_COMMAND) bash -c "./vendor/bin/rector -n"

.PHONY: rector-apply
rector-apply:
	$(DOCKER_COMPOSE_COMMAND) bash -c "./vendor/bin/rector"

.PHONY: start
start:
	docker compose up -d

.PHONY: start-rebuild
start-rebuild:
	docker compose up -d --build

.PHONY: stop
stop:
	docker compose down

.PHONY: tests
tests:
	$(DOCKER_COMPOSE_COMMAND) bash -c "./vendor/bin/phpunit"

.PHONY: validate
validate:
	$(DOCKER_COMPOSE_COMMAND) bash -c "./bin/console doctrine:schema:validate"

.PHONY: tailwind-build
tailwind-build:
	$(DOCKER_COMPOSE_COMMAND) bash -c "./bin/console tailwind:build"

.PHONY: tailwind-watch
tailwind-watch:
	$(DOCKER_COMPOSE_COMMAND) bash -c "./bin/console tailwind:build --watch"


.PHONY: reset-dev-database
reset-dev-database:
	$(DOCKER_COMPOSE_COMMAND) bash -c "APP_ENV=dev ./bin/console doctrine:database:drop --force"
	$(DOCKER_COMPOSE_COMMAND) bash -c "APP_ENV=dev ./bin/console doctrine:database:create"
	$(DOCKER_COMPOSE_COMMAND) bash -c "APP_ENV=dev ./bin/console doctrine:migrations:migrate --no-interaction"
	$(DOCKER_COMPOSE_COMMAND) bash -c "APP_ENV=dev ./bin/console doctrine:fixtures:load --no-interaction"