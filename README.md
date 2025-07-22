# 📰 Blog Search App (Laravel + Vue 3 + Sphinx + Docker)

## 📦 Features

- ✅ Laravel 12 REST API
- ✅ Vue 3 frontend (Vite)
- ✅ MySQL with strict Eloquent models
- ✅ Many-to-many post/tag relationship
- ✅ Sphinx full-text search engine integration

---

## 🛠️ Tech Stack

- **Backend:** Laravel 12, MySQL 8.0, PHP 8.2
- **Frontend:** Vue 3, Vite, TailwindCSS
- **Search Engine:** Sphinx (via `sphinxsearch`)
- **Containerization:** Docker + Docker Compose
- **Others:** RESTful routing, Eloquent ORM

---

## 🚀 Getting Started

### 1. Clone the Repository

```bash
git clone https://github.com/lawrencexbaron/blog-app.git
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

DB_SHARD1_HOST=shard1-mysql
DB_SHARD1_DATABASE=blog_shard1
DB_SHARD1_USERNAME=user
DB_SHARD1_PASSWORD=password
DB_SHARD1_PORT=3306

DB_SHARD2_HOST=shard2-mysql
DB_SHARD2_DATABASE=blog_shard2
DB_SHARD2_USERNAME=user
DB_SHARD2_PASSWORD=password
DB_SHARD2_PORT=3306



docker exec blog-app php artisan key:generate
docker exec blog-app php artisan migrate --seed
```

### 5. Index data with Sphinx

```bash
docker exec sphinx indexer --all --rotate
```
