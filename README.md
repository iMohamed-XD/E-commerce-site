# Mahly (محلي) - E-commerce Platform 🛍️

Mahly is a premium, localized e-commerce platform designed for small businesses and individual sellers. Built with Laravel, Tailwind CSS, and Alpine.js, it offers a glassmorphic dark-themed interface with advanced manual controls for products, discounts, and promo codes.

---

## ✨ Features

- **Store Management**: Create a custom shop with a unique slug and live link preview.
- **Premium UI**: Dark-themed dashboard with glassmorphism and modern icons (Heroicons).
- **Product Controls**: Manual toggle for discounts and product visibility.
- **Promo Codes**: Toggle-based activation for marketing campaigns.
- **Interactive Cart**: Non-intrusive cart animations and toast notifications.
- **Localization**: RTL support with premium Arabic typography (Tajawal font).

---

## 🛠️ Setup Instructions for Collaborators

If you are cloning this project to work on it, follow these steps:

### 1. Prerequisites
Ensure you have the following installed:
- [PHP 8.2+](https://www.php.net/downloads)
- [Composer](https://getcomposer.org/)
- [Node.js & NPM](https://nodejs.org/)
- [PostgreSQL](https://www.postgresql.org/) (or your preferred DB)

### 2. Initial Setup
```bash
# Clone the repository
git clone <repository-url>
cd E-commerce-site

# Install PHP dependencies
composer install

# Install JS dependencies
npm install

# Create environment file
cp .env.example .env
```

### 3. Database & App Keys
1. Open `.env` and configure your database credentials.
2. Run the following commands:
```bash
# Generate app key
php artisan key:generate

# Run migrations and seed data (if any)
php artisan migrate

# Link storage for images
php artisan storage:link
```

### 4. Running the App
```bash
# Start the local server
php artisan serve

# Run Vite dev server for styling
npm run dev
```

---

## 🚀 Deployment & Usage
- Access the dashboard at `http://localhost:8000/dashboard`.
- Use `php artisan tinker` for direct DB interaction or testing.

## 📄 License
This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
