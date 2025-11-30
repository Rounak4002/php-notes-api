# php-notes-api

A simple RESTful Notes API implemented in PHP. This repository provides a minimal example of how to build CRUD endpoints for managing notes (create, read, update, delete) using plain PHP (no framework) and a MySQL database. It’s ideal for learning, small demos, or as a starting point for a larger project.

---

## Features

* Create, read (single & list), update, and delete notes
* JSON request and response bodies
* Simple routing and input validation
* MySQL database persistence
* Minimal dependencies so it's easy to understand and extend

---

## Tech stack

* PHP 7.4+ (or PHP 8.x)
* MySQL / MariaDB
* (Optional) Composer for dependency management
* Plain PHP code — no framework required

---

## Quick Start / Installation

1. Clone the repository:

```bash
git clone https://github.com/Rounak4002/php-notes-api.git
cd php-notes-api
```

2. Ensure you have PHP and MySQL installed. Example versions tested: PHP 7.4+, MySQL 5.7+ / 8.0.

3. Create a database and table. Example SQL:

```sql
CREATE DATABASE notes_api;
USE notes_api;

CREATE TABLE notes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  content TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

4. Copy the example environment/config file and update credentials (or add DB credentials directly to the config file used by the project):

```bash
cp .env.example .env
# edit .env and set DB_HOST, DB_NAME, DB_USER, DB_PASS
```

> If there is no `.env.example` in the repo, create a `config.php` or similar file and export DB credentials there.

5. Start PHP built-in server for local testing (point to project public directory if present):

```bash
php -S localhost:8000
```

Open `http://localhost:8000/` and use the API endpoints described below.

---

## Configuration

Typical environment variables or config values (adjust to your project layout):

```
DB_HOST=127.0.0.1
DB_NAME=notes_api
DB_USER=root
DB_PASS=secret
BASE_URL=http://localhost:8000
```

If your repo uses a `config.php`, add these values there and include the file from your entry script.

---

## API Endpoints

> Replace `BASE_URL` with your server host.

### List notes

```
GET /notes
```

Response (200):

```json
[
  {
    "id": 1,
    "title": "Buy groceries",
    "content": "Milk, eggs, bread",
    "created_at": "2025-11-30 12:00:00",
    "updated_at": "2025-11-30 12:00:00"
  }
]
```

### Get single note

```
GET /notes/{id}
```

Response (200):

```json
{
  "id": 1,
  "title": "Buy groceries",
  "content": "Milk, eggs, bread",
  "created_at": "2025-11-30 12:00:00",
  "updated_at": "2025-11-30 12:00:00"
}
```

If not found (404):

```json
{ "error": "Note not found" }
```

### Create note

```
POST /notes
Content-Type: application/json
```

Body:

```json
{ "title": "New note", "content": "Note details..." }
```

Response (201):

```json
{ "id": 2, "message": "Note created" }
```

### Update note

```
PUT /notes/{id}
Content-Type: application/json
```

Body (any fields to update):

```json
{ "title": "Updated title", "content": "Updated content" }
```

Response (200):

```json
{ "message": "Note updated" }
```

If not found (404):

```json
{ "error": "Note not found" }
```

### Delete note

```
DELETE /notes/{id}
```

Response (200):

```json
{ "message": "Note deleted" }
```

If not found (404):

```json
{ "error": "Note not found" }
```

---

## Example cURL requests

Create:

```bash
curl -X POST http://localhost:8000/notes \
  -H "Content-Type: application/json" \
  -d '{"title":"Read book","content":"Read chapter 4"}'
```

Get list:

```bash
curl http://localhost:8000/notes
```

Update:

```bash
curl -X PUT http://localhost:8000/notes/1 \
  -H "Content-Type: application/json" \
  -d '{"title":"Updated"}'
```

Delete:

```bash
curl -X DELETE http://localhost:8000/notes/1
```

---

## Error handling & validation

* The API should return appropriate HTTP status codes (200, 201, 400, 404, 500).
* Validate required fields (e.g., `title` should be present when creating a note).
* Return JSON error messages with helpful information for clients.

---

## Testing

* Manual testing with Postman, curl, or HTTPie is recommended for small projects.
* Add unit/integration tests if you plan to extend this project.

---

## Extending the project (ideas)

* Add user authentication (token-based, JWT)
* Add search, pagination, and sorting for notes
* Use a microframework (Slim, Lumen) or a full framework (Laravel) for heavy features
* Add request validation library and structured routing
* Add Dockerfile and docker-compose for easy local setup

---

## Contributing

Contributions are welcome. If you find bugs or want to add features:

1. Fork the repo
2. Create a feature branch (`git checkout -b feature/name`)
3. Commit your changes (`git commit -m "Add feature"`)
4. Push to your branch and open a Pull Request

Please include clear commit messages and tests for new features when possible.

---

## License

If the repository does not include a license file, add one (MIT recommended for simple demos). Example: `LICENSE` with the MIT license text.

---

## Author - ROUNAK PATTANAIK | ITER SOA UNIVERSITY

---

