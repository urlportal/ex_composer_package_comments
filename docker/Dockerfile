FROM php:8.1-cli-alpine

ARG CONTAINER_EXEC_USER_ID=1000
ARG CONTAINER_EXEC_GROUP_ID=1000

RUN apk update --no-check-certificate && apk add --no-cache --allow-untrusted ca-certificates && update-ca-certificates

RUN apk add --no-cache shadow && \
    groupmod -o -g $CONTAINER_EXEC_GROUP_ID www-data && \
    usermod -o -u $CONTAINER_EXEC_USER_ID -g www-data www-data && \
    apk del shadow;

COPY --from=composer:2.9.5 /usr/bin/composer /usr/local/bin/composer

WORKDIR /app
