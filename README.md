# Notes API

API для управления заметками с аутентификацией через Laravel Passport. Реализован на базе Laravel 12 с модульной архитектурой.

## Требования

- Docker и Docker Compose (рекомендуется)
- PHP >= 8.2 (для локальной разработки без Docker)
- Composer (для локальной разработки без Docker)

## Быстрый старт с Docker

### 1. Клонирование репозитория

```bash
git clone https://github.com/KirVelikiyy/-SE-test_assigment test_assigment
cd test_assigment
```

### 2. Настройка окружения

Скопируйте и настройте файл `.env` в директории `docker/`:

```bash
cd docker
cp .env.example .env  # если есть пример, или создайте файл вручную
```

Отредактируйте `docker/.env` и настройте необходимые переменные окружения (например, пароли БД, ключи приложения).

### 3. Запуск контейнеров

```bash
cd docker
docker compose up -d
```

Это запустит:
- **backend** - PHP приложение (порт 8000)
- **postgres** - PostgreSQL база данных (работает только в bridge сети, без проброса портов на хост)

### 4. Установка зависимостей и миграции

Выполните команды внутри контейнера backend:

```bash
cd docker
docker compose exec backend composer install
docker compose exec backend php artisan key:generate
docker compose exec backend php artisan migrate
docker compose exec backend php artisan passport:keys
docker compose exec backend php artisan db:seed
```

### 5. Доступ к приложению

После запуска приложение будет доступно по адресу: `http://localhost:8000`

## Установка и настройка (без Docker)

### 1. Клонирование репозитория

```bash
git clone https://github.com/KirVelikiyy/-SE-test_assigment test_assigment
cd test_assigment/backend
```

### 2. Установка зависимостей

```bash
composer install
```

### 3. Настройка окружения

Скопируйте файл `.env.example` в `.env`:

```bash
cp .env.example .env
```

Сгенерируйте ключ приложения:

```bash
php artisan key:generate
```

Настройте переменные окружения в файле `.env`:

```env
APP_NAME="Notes API"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=sqlite
# или для MySQL/PostgreSQL:
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=notes_db
# DB_USERNAME=root
# DB_PASSWORD=

MAIL_MAILER=log
MAIL_ADMIN_RECIPIENT=admin@example.com
```

### 4. Создание базы данных

Для SQLite создайте файл базы данных:

```bash
touch database/database.sqlite
```

Или настройте подключение к MySQL/PostgreSQL в `.env`.

### 5. Запуск миграций

```bash
php artisan migrate
```

### 6. Установка Laravel Passport

Создайте ключи шифрования для Passport:

```bash
php artisan passport:keys
```

Или установите Passport с автоматической настройкой:

```bash
php artisan install:api --passport
```

### 7. Заполнение базы данных тестовыми данными

Запустите сидеры для создания тестовых пользователей и заметок:

```bash
php artisan db:seed
```

Это создаст:
- Администратора: `admin@example.com` / `password`
- 5-10 обычных пользователей (все с паролем `password`)
- По 5-10 заметок для каждого пользователя

Для полного пересоздания базы данных:

```bash
php artisan migrate:fresh --seed
```

## Запуск приложения

### С Docker

```bash
cd docker
docker compose up -d          # Запуск в фоне
docker compose up             # Запуск с выводом логов
docker compose down           # Остановка контейнеров
docker compose restart        # Перезапуск контейнеров
```

### Без Docker (режим разработки)

Запустите встроенный сервер Laravel:

```bash
php artisan serve
```

Приложение будет доступно по адресу: `http://localhost:8000`

## API Endpoints

API доступно по адресу: `http://localhost:8000/api`

#### Основные маршруты:

- `POST /oauth/token` - Получение OAuth2 токена
- `GET /api/notes` - Получить все заметки текущего пользователя
- `POST /api/notes` - Создать новую заметку
- `GET /api/notes/{id}` - Получить заметку по ID
- `PUT /api/notes/{id}` - Обновить заметку
- `DELETE /api/notes/{id}` - Удалить заметку

## Документация API

### Swagger UI

После генерации документации доступна по адресу:

```
http://localhost:8000/api/documentation
```

Для генерации/обновления документации:

**С Docker:**
```bash
cd docker
docker compose exec backend php artisan l5-swagger:generate
```

**Без Docker:**
```bash
php artisan l5-swagger:generate
```

### Получение токена доступа

Для получения токена доступа используйте OAuth2 Password Grant:

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

**Важно:** Сначала создайте OAuth Client:

**С Docker:**
```bash
cd docker
docker compose exec backend php artisan passport:client --password
```

**Без Docker:**
```bash
php artisan passport:client --password
```

## Тестирование

### С Docker

```bash
cd docker
docker compose exec backend php artisan test
```

Запуск конкретного теста:

```bash
docker compose exec backend php artisan test --filter=CreateNoteTest
```

### Без Docker

Запуск всех тестов:

```bash
php artisan test
```

Запуск конкретного теста:

```bash
php artisan test --filter=CreateNoteTest
```

Запуск тестов с покрытием:

```bash
php artisan test --coverage
```

## Структура проекта

```
test_assigment/
├── backend/                 # Laravel приложение
│   ├── app/
│   ├── modules/
│   │   ├── notes/          # Модуль управления заметками
│   │   └── Notifications/  # Модуль уведомлений
│   ├── database/
│   ├── tests/
│   └── ...
├── docker/                  # Docker конфигурация
│   ├── docker-compose.yml  # Конфигурация Docker Compose
│   └── .env                # Переменные окружения для Docker сервисов
└── Dockerfile              # Dockerfile для backend
```

### Модули

- `modules/notes/` - Модуль управления заметками
  - `src/Commands/` - Команды (Create, Update, Delete, Get, GetAll)
  - `src/Events/` - События (NoteCreated, NoteUpdated, NoteDeleted)
  - `src/Http/Actions/` - HTTP обработчики
  - `src/Models/` - Модели
  - `src/Repositories/` - Репозитории
  - `src/Exceptions/` - Исключения

- `modules/Notifications/` - Модуль уведомлений
  - `src/Mail/` - Email классы
  - `src/Listeners/` - Обработчики событий
  - `src/Providers/` - Сервис-провайдеры

### Основные компоненты

- `app/Http/Controllers/` - Контроллеры (включая Swagger документацию)
- `app/Models/` - Модели приложения (User)
- `app/Enums/` - Перечисления (UserRole)
- `app/Repositories/` - Репозитории (UserRepository)
- `database/seeders/` - Сидеры для заполнения БД
- `database/factories/` - Фабрики для тестовых данных
- `tests/Feature/` - Функциональные тесты

## Docker команды

### Работа с контейнерами

```bash
cd docker

# Запуск
docker compose up -d

# Остановка
docker compose down

# Просмотр логов
docker compose logs -f backend
docker compose logs -f postgres

# Перезапуск
docker compose restart backend

# Выполнение команд внутри контейнера
docker compose exec backend php artisan migrate
docker compose exec backend composer install
docker compose exec backend php artisan test
```

### Работа с базой данных

```bash
cd docker

# Подключение к PostgreSQL
docker compose exec postgres psql -U ${DB_USERNAME} -d ${DB_DATABASE}

# Выполнение миграций
docker compose exec backend php artisan migrate

# Запуск сидеров
docker compose exec backend php artisan db:seed

# Пересоздание БД с сидерами
docker compose exec backend php artisan migrate:fresh --seed
```

## Особенности

### Роли пользователей

- **Admin** - может управлять заметками всех пользователей
- **User** - может управлять только своими заметками

### Аутентификация

Аутентификация реализована через Laravel Passport (OAuth2). Все API endpoints защищены middleware `auth:api`.

### Уведомления

При создании новой заметки администратору отправляется email-уведомление (асинхронно через очередь).

### Архитектура

Проект использует модульную архитектуру монолита:
- Каждый модуль независим
- Связь между модулями через события
- Использование Command Bus паттерна
- Разделение на слои (Commands, Actions, Repositories)

## Полезные команды

### С Docker

```bash
cd docker

# Очистка кэша
docker compose exec backend php artisan cache:clear
docker compose exec backend php artisan config:clear
docker compose exec backend php artisan route:clear

# Пересоздание базы данных с сидерами
docker compose exec backend php artisan migrate:fresh --seed

# Генерация документации API
docker compose exec backend php artisan l5-swagger:generate

# Создание OAuth клиента
docker compose exec backend php artisan passport:client --password

# Просмотр маршрутов
docker compose exec backend php artisan route:list

# Запуск очереди (для асинхронных задач)
docker compose exec backend php artisan queue:work
```

### Без Docker

```bash
# Очистка кэша
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Пересоздание базы данных с сидерами
php artisan migrate:fresh --seed

# Генерация документации API
php artisan l5-swagger:generate

# Создание OAuth клиента
php artisan passport:client --password

# Просмотр маршрутов
php artisan route:list

# Запуск очереди (для асинхронных задач)
php artisan queue:work
```

## Конфигурация Docker

### Структура Docker конфигурации

- `docker/docker-compose.yml` - Конфигурация Docker Compose
- `docker/.env` - Переменные окружения для сервисов (включая настройки БД, приложения и др.)

### Особенности конфигурации

- **PostgreSQL** работает только в network bridge (без проброса портов на хост)
- **Backend** имеет проброс портов `8000:8000` на хост для удобства разработки
- Все сервисы используют общую сеть `app-network` (bridge driver)
- Данные PostgreSQL сохраняются в volume `postgres_data`
- Файл `.env` из директории `docker/` монтируется в контейнер backend как `/var/www/html/.env`

## Разработка

### Code Style

Проект использует Laravel Pint для форматирования кода:

**С Docker:**
```bash
cd docker
docker compose exec backend ./vendor/bin/pint
```

**Без Docker:**
```bash
./vendor/bin/pint
```

### Структура модулей

Каждый модуль следует структуре:
- `Commands/` - бизнес-логика (Command + Handler)
- `Http/Actions/` - HTTP слой (Action + Request + Response)
- `Repositories/` - доступ к данным
- `Models/` - Eloquent модели
- `Events/` - события домена
- `Exceptions/` - кастомные исключения
- `Providers/` - сервис-провайдеры модуля

## Лицензия

MIT
