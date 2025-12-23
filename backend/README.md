# Notes API

API для управления заметками с аутентификацией через Laravel Passport. Реализован на базе Laravel 12 с модульной архитектурой.

## Требования

- PHP >= 8.2
- Composer
- SQLite / MySQL / PostgreSQL
- Node.js и NPM (опционально, для фронтенда)

## Установка и настройка

### 1. Клонирование репозитория

```bash
git clone <repository-url>
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

### Режим разработки

Запустите встроенный сервер Laravel:

```bash
php artisan serve
```

Приложение будет доступно по адресу: `http://localhost:8000`

### API Endpoints

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

```bash
php artisan passport:client --password
```

## Тестирование

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

## Разработка

### Code Style

Проект использует Laravel Pint для форматирования кода:

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
