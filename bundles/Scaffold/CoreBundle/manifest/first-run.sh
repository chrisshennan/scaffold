#!/usr/bin/env bash

set -o pipefail

PROJECT_DIR=${PWD}
MANIFEST_DIR=${PROJECT_DIR}/$(dirname "$0")

cp -R ${MANIFEST_DIR}/config/docker ${PROJECT_DIR}/config/docker
cp ${MANIFEST_DIR}/Makefile ${PROJECT_DIR}/Makefile
cp ${MANIFEST_DIR}/Dockerfile ${PROJECT_DIR}/Dockerfile
cp ${MANIFEST_DIR}/compose.yaml ${PROJECT_DIR}/compose.yaml

docker compose run --rm php bash -c "composer install"
docker compose run --rm php bash -c "./bin/console scaffold:asset-manager:sync"
docker compose run --rm php bash -c "./bin/console importmap:install"
docker compose run --rm php bash -c "./bin/console doctrine:migrations:diff"
docker compose run --rm php bash -c "./bin/console doctrine:migrations:migrate -y"

make start
