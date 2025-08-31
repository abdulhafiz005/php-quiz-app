# Laravel Project

## Setup Instructions

```bash
# Clone repository
git clone https://github.com/abdulhafiz005/php-quiz-app.git
cd php-quiz-app

# Install dependencies
composer install

# Copy environment file
cp .env.example .env

# Change session driver in .env
SESSION_DRIVER=file

# Run migrations with seed
php artisan migrate --seed

# Start development server
php artisan serve
