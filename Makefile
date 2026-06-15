# Цели Makefile повторяют пользовательские composer-скрипты, чтобы команда make
# и команда composer были взаимозаменяемыми точками входа. Halt-on-first-failure
# обеспечивает сам composer внутри скрипта all.
# Все команды выполняются внутри docker-контейнера сервиса app.

DOCKER_RUN = docker compose run --rm app

.PHONY: test stan cs cs-fix rector rector-fix all

test:
	$(DOCKER_RUN) composer test

stan:
	$(DOCKER_RUN) composer stan

cs:
	$(DOCKER_RUN) composer cs

cs-fix:
	$(DOCKER_RUN) composer cs-fix

rector:
	$(DOCKER_RUN) composer rector

rector-fix:
	$(DOCKER_RUN) composer rector-fix

all:
	$(DOCKER_RUN) composer all
