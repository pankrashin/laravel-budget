# Laravel Budget

A full-featured personal budgeting Progressive Web App (PWA) built with Laravel. The entire application stack is containerized with Docker and Nginx. Features include multi-currency accounts, transaction management, dynamic net worth calculation via currency conversion API, and a data-driven dashboard with Chart.js.

## Usage

### Clone the Repository

```
$ git clone https://github.com/pankrashin/laravel-budget.git
$ cd laravel-budget
```

### Edit .env

```
$ vim /src/.env
```

### Run Docker Compose

```
$ docker-compose up --build -d
```

### Generate Application Key

```
$ docker-compose exec app php artisan key:generate
```

### Clear Caches

```
$ docker-compose exec app php artisan config:clear
$ docker-compose exec app php artisan route:clear
$ docker-compose exec app php artisan view:clear
```

### Run the Database Migration

```
$ docker-compose exec app php artisan migrate
```
