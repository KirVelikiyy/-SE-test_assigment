# Notes API

API для управления заметками с аутентификацией через Laravel Passport. Реализован на базе Laravel 12 с модульной архитектурой.

## Быстрый старт

### 1. Клонирование и запуск

```bash
git clone https://github.com/KirVelikiyy/-SE-test_assigment test_assigment
cd test_assigment/docker
cp .env.example .env  # если есть пример, или создайте файл вручную
docker compose up -d
```

### 2. Установка зависимостей и настройка

```bash
docker compose exec backend composer install
docker compose exec backend php artisan key:generate
docker compose exec backend php artisan migrate
docker compose exec backend php artisan passport:keys
docker compose exec backend php artisan db:seed
```

### 3. Готово!

Приложение доступно по адресу: **http://localhost:8000**

## Запуск тестов

```bash
cd docker
docker compose exec backend php artisan test
```

Запуск конкретного теста:

```bash
docker compose exec backend php artisan test --filter=CreateNoteTest
```

## Запуск сидеров

```bash
cd docker
docker compose exec backend php artisan db:seed
```

Для полного пересоздания базы данных с сидерами:

```bash
docker compose exec backend php artisan migrate:fresh --seed
```

## Документация API

### Генерация документации

```bash
cd docker
docker compose exec backend php artisan l5-swagger:generate
```

Документация будет доступна по адресу: **http://localhost:8000/api/documentation**

### Тестирование API вручную

#### 1. Создание OAuth клиента

```bash
cd docker
docker compose exec backend php artisan passport:client --password
```

Сохраните полученные `client_id` и `client_secret`.

#### 2. Получение токена доступа

```bash
curl -X POST http://localhost:8000/oauth/token \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -d '{
    "grant_type": "password",
    "client_id": "YOUR_CLIENT_ID",
    "client_secret": "YOUR_CLIENT_SECRET",
    "username": "admin@example.com",
    "password": "password",
    "scope": ""
  }'
```

Сохраните полученный `access_token` из ответа.

#### 3. Использование токена для запросов

**Получить все заметки:**
```bash
curl -X GET http://localhost:8000/api/notes \
  -H "Accept: application/json" \
  -H "Authorization: Bearer YOUR_ACCESS_TOKEN"
```

**Создать заметку:**
```bash
curl -X POST http://localhost:8000/api/notes \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_ACCESS_TOKEN" \
  -d '{
    "title": "Моя заметка",
    "body": {"text": "Текст заметки"}
  }'
```

**Получить заметку по ID:**
```bash
curl -X GET http://localhost:8000/api/notes/1 \
  -H "Accept: application/json" \
  -H "Authorization: Bearer YOUR_ACCESS_TOKEN"
```

**Обновить заметку:**
```bash
curl -X PUT http://localhost:8000/api/notes/1 \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_ACCESS_TOKEN" \
  -d '{
    "title": "Обновленная заметка",
    "body": {"text": "Обновленный текст"}
  }'
```

**Удалить заметку:**
```bash
curl -X DELETE http://localhost:8000/api/notes/1 \
  -H "Accept: application/json" \
  -H "Authorization: Bearer YOUR_ACCESS_TOKEN"
```

### Тестовые пользователи

После выполнения `php artisan db:seed` будут созданы:
- **Администратор**: `admin@example.com` / `password`
- **Обычные пользователи**: несколько пользователей с паролем `password`
