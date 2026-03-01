# Invoice API

A Laravel-based API for managing contracts, invoices, and payments with multi-tenant support.

## Features
- Multi-tenant contract and invoice management
- Invoice creation, payment recording, and contract summaries
- Authorization policies to restrict access by tenant
- Tax calculation via pluggable services

## Requirements
- PHP >= 8.1
- Composer
- Laravel >= 10
- MySQL or compatible database

## Setup

1. **Clone the repository:**
	```sh
	git clone https://github.com/Muhammad-Suleman-Hamza/Invoice-Management-API-Laravel.git
	cd invoice-api
	```

2. **Install dependencies:**
	```sh
	composer install
	```

3. **Copy and configure environment:**
	```sh
	cp .env.example .env
	# Edit .env with your database and app settings
	```

4. **Generate application key:**
	```sh
	php artisan key:generate
	```

5. **Run migrations:**
	```sh
	php artisan migrate
	```

6. **Seed the database:**
	```sh
	php artisan db:seed --class=TestSeeder
	```

## Usage

- Register and login via `/api/register` and `/api/login` endpoints
- Use the provided API routes for contracts, invoices, and payments
- All protected routes require authentication via Laravel Sanctum
