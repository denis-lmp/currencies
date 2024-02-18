.PHONY: docker-build docker-up composer-install key-generate migrate seed currency-update install down frontend

# Build
docker-build:
	cp .env.example .env

# Start Docker Compose
docker-up:
	docker-compose up -d

# Install Composer dependencies
composer-install:
	docker-compose exec app composer install

key-generate:
	docker-compose exec app php artisan key:generate

# Run migrations
migrate:
	docker-compose exec app php artisan migrate

# Run seeders
seed:
	docker-compose exec app php artisan db:seed

# Get currency rates for default banks
currency-update:
	docker-compose exec app php artisan currency:update

#run frontend
frontend:
	npm run dev

# Stop
down:
	docker-compose down

# Install dependencies, run Docker Compose, migrations, and seeders
install: docker-build docker-up composer-install key-generate migrate seed currency-update frontend
