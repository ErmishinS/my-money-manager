# My-Money-Manager (MMM)

**My Money Manager** is a web application for managing personal finances. It allows users to create income/expense categories, manage payments, currencies, and payment types, and track financial habits with ease.

## ⚙️ Technologies

- [Laravel 10](https://laravel.com/)
- [MySQL 8](https://www.mysql.com/)
- [Vite](https://vitejs.dev/) + [Tailwind CSS](https://tailwindcss.com/)
- [Docker + Docker Compose](https://docs.docker.com/compose/)
- Node.js + npm (inside the container)
- PHP 8.4

---

## Getting Started

### 1. Clone the Repository

```bash
git clone https://github.com/ErmishinS/my-money-manager.git
cd my-money-manager
```
### 2. Set Up Environment

```bash
cp .env.example .env
```

### 3. Start the Application

```bash
docker compose up --build
```

### 4. Run migrations

```bash
docker exec -it laravel_app php artisan migrate
```

Or you can run migration with seeders to fill the database with data:

```bash
docker exec -it laravel_app php artisan migrate:fresh --seed
```

