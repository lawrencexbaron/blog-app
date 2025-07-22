# 📰 Blog Search App (Laravel + Vue 3 + Sphinx + Docker)

## 📦 Features

- ✅ Laravel 12 REST API
- ✅ Vue 3 frontend (Vite)
- ✅ MySQL with strict Eloquent models
- ✅ Many-to-many post/tag relationship
- ✅ Sphinx full-text search engine integration
- ✅ Dockerized for local development
- ✅ Modular structure for scalability

---

## 🛠️ Tech Stack

- **Backend:** Laravel 10, MySQL, PHP 8.2
- **Frontend:** Vue 3, Vite, TailwindCSS
- **Search Engine:** Sphinx (via `sphinxsearch`)
- **Containerization:** Docker + Docker Compose
- **Others:** Laravel Sail (optional), RESTful routing, Eloquent ORM

---

## 🚀 Getting Started

### 1. Clone the Repository

```bash
git clone https://github.com/your-username/blog-sphinx-app.git
cd blog-sphinx-app
```

### 2. Start Docker Containers

```bash
docker-compose up --build -d
```

### 3. Install Backend Dependencies

```bash
docker-compose exec blog-app composer install
docker-compose exec blog-app php artisan key:generate
docker-compose exec blog-app php artisan migrate:fresh --seed
```

### 4. Setup backend environment

```bash
cp .env.example .env

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=blog-app
DB_USERNAME=user
DB_PASSWORD=password


docker exec blog-app php artisan key:generate
docker exec blog-app php artisan migrate --seed
```

### 5. Index data with Sphinx

```bash
docker exec sphinx indexer --all --rotate
```
