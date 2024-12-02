# Laravel Application - Local Environment Setup

Follow the steps below to set up and run the Laravel application in your local environment.

---

## Prerequisites

Ensure you have the following installed on your system:
- **PHP 8.2+**
- **Composer**
- **SQLite**
- **Git**

---

## Installation Steps

Run the following commands in your terminal to set up the application:

```bash
# 1. Clone the repository
git clone git@github.com:VBundziak/import-shopify-orders.git
cd import-shopify-orders

# 2. Create the .env file
php -r "file_exists('.env') || copy('.env.example', '.env');"

# 3. Install PHP dependencies
composer install

# 4. Generate the application key
php artisan key:generate

# 5. Run migrations and seed data
php artisan migrate

# 6. Start the Laravel development server
php artisan serve

# 7. Update .env to correct Shopify API url, key and password
SHOPIFY_API_KEY=shopify_api_key
SHOPIFY_API_PASSWORD=shopify_api_password
SHOPIFY_BASE_URL=https://shopify-store.myshopify.com

# Visit the application at http://127.0.0.1:8000
