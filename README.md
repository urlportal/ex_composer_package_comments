# Тестовое задание "comments client"

PHP-библиотека HTTP-клиента для REST-сервиса комментариев `example.com`

## Установка

```bash
composer require vendor/comments-client
```

## Разработчикам

1. Установка зависимостей:
   ```bash
   docker compose run --rm app composer install
   ```

2. Запуск тестов:
   ```bash
   docker compose run --rm app vendor/bin/phpunit
   ```
