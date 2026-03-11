## Todo REST API
### Возможности

- **DDD-слои**:
  - `Domain` — сущности, enum’ы, доменные исключения.
  - `Application` — сервисы и DTO (use-case’ы).
  - `Infrastructure` — репозитории и Eloquent-модели.
  - `Presentation` — контроллеры, form-request’ы, API-ответы.
- **CRUD по задачам и тегам**:
  - задачи: создание, список, получение по ID, обновление, удаление;
  - теги: создание, список, получение по ID, обновление, удаление.
- **Работа с тегами задач**:
  - прикрепление / открепление тега;
  - полная синхронизация списка тегов.
- **Документация Swagger (L5-Swagger)**:
  - генерация OpenAPI-спеки;
  - UI по адресу `/api/documentation`.

### Стек

- **PHP** 8.x  
- **Laravel** 11.x  
- **MySQL/PostgreSQL** (в зависимости от настроек `.env`)  
- **Docker / docker-compose** (опционально)  
- **L5-Swagger** (`darkaonline/l5-swagger`)

### Запуск проекта

#### Локально

```bash
cp .env.example .env
# настрой параметры подключения к БД в .env

composer install
php artisan key:generate
php artisan migrate --seed

php artisan serve
```

Приложение будет доступно по адресу `http://127.0.0.1:8000`.

#### Через Docker

```bash
docker-compose up -d --build
```

Порт смотри в `docker-compose.yml` (например, `http://localhost:8080`).

### Документация Swagger

Сгенерировать спецификацию:

```bash
php artisan l5-swagger:generate
```

Открыть в браузере:

- `http://127.0.0.1:8000/api/documentation`  
  или `http://localhost:<порт>/api/documentation` при запуске через Docker.

### Основные эндпоинты

Базовый префикс API: **`/api/v1`**

- **Задачи**
  - `GET /tasks` — список задач;
  - `GET /tasks/{id}` — получить задачу по ID;
  - `POST /tasks` — создать задачу;
  - `PATCH /tasks/{id}` — обновить задачу;
  - `DELETE /tasks/{id}` — удалить задачу;
  - `POST /tasks/{taskId}/tags/{tagId}` — прикрепить тег;
  - `DELETE /tasks/{taskId}/tags/{tagId}` — открепить тег;
  - `PUT /tasks/{taskId}/tags` — синхронизировать список тегов.

- **Теги**
  - `GET /tags` — список тегов;
  - `GET /tags/{id}` — получить тег по ID;
  - `POST /tags` — создать тег;
  - `PATCH /tags/{id}` — обновить тег;
  - `DELETE /tags/{id}` — удалить тег.

Все успешные ответы обёрнуты в форму:

```json
{
  "success": true,
  "data": {}
}
```

Ошибки:

```json
{
  "success": false,
  "data": "Сообщение об ошибке"
}
```

### Структура проекта (DDD)

- `app/Domain` — доменные сущности, enum’ы, исключения.
- `app/Application` — сервисы и DTO.
- `app/Infrastructure` — репозитории и модели работы с БД.
- `app/Presentation` — контроллеры, form-request’ы и HTTP-слой.
- `app/Swagger` — OpenAPI-конфигурация и схемы (PHP-атрибуты).

Такое разделение упрощает поддержку и развитие проекта, а также демонстрирует практический пример DDD в контексте Laravel.
