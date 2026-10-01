default:
	@echo 'Enter command'

start: \
	down \
	git-pull \
	generate-certs \
	up
s: start

down:
	docker compose down -v --remove-orphans
d: down

git-pull:
	git pull

generate-certs:
	if [ ! -f ./.docker/certs/localhost-cert.pem ]; then \
		cd .docker/certs && \
		mkcert -cert-file localhost-cert.pem -key-file localhost-key.pem \
		127.0.0.1 localhost localhost.localhost :: ::1; \
	fi

up:
	docker compose up -d --build --remove-orphans

restart: down up

sh:
	docker compose exec php-fpm sh

sh-node:
	docker compose exec node sh

rebuild-mariadb:
	docker compose build --no-cache mariadb
	docker compose up -d --build --remove-orphans

rebuild-php-fpm:
	docker compose build --no-cache php-fpm
	docker compose stop nginx
	docker compose up -d --build --remove-orphans

rebuild-nginx:
	docker compose build --no-cache nginx
	docker compose stop nginx
	docker compose up -d --build --remove-orphans

db-export-gz:
	docker compose exec mariadb sh -c 'su dockerUser -c "mariadb-dump -u root -p\"$$MARIADB_ROOT_PASSWORD\" database | gzip > database.sql.gz"'

db-import-gz:
	docker compose exec mariadb sh -c 'pv database.sql.gz | zcat | mariadb -p"$$MARIADB_ROOT_PASSWORD" database'

db-export-sql:
	docker compose exec mariadb sh -c 'su dockerUser -c "mariadb-dump -u root -p\"$$MARIADB_ROOT_PASSWORD\" database > database.sql"'

db-import-sql:
	docker compose exec mariadb sh -c 'pv database.sql | mariadb -p"$$MARIADB_ROOT_PASSWORD" database'

# ----------------------------------------------------------------------------------------------------------------------
