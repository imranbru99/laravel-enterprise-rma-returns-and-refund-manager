# Local Installation & Setup

## Prerequisites
- **PHP**: 8.2 or higher (8.4+ recommended, tested on PHP 8.5)
- **Composer**: 2.2+
- **Node.js**: 20+ and npm
- **Database**: SQLite (local development) or MySQL 8.0+ / PostgreSQL 14+
- **Redis** (optional in local dev, recommended for queues & cache)

## Step-by-Step Installation

### 1. Clone the Repository
```bash
git clone https://github.com/imranbru99/laravel-enterprise-rma-returns-and-refund-manager.git
cd laravel-enterprise-rma-returns-and-refund-manager
```

### 2. Configure Environment
```bash
cp .env.example .env
php artisan key:generate
```

### 3. Install Composer Dependencies
```bash
composer install
```

### 4. Install Front-End Dependencies & Build Assets
```bash
npm install
npm run build
```

### 5. Run Database Migrations & Seeders
```bash
php artisan migrate:fresh --seed
```
This populates:
- 3 Realistic Demo Tenants (RetailSphere Omnichannel, TechCare Electronics, GlobalReturns 3PL)
- 9 Demo User Accounts (Super Admin, Branch Managers, Finance Managers, Inspectors, Customers)
- Sample Products, Orders, RMAs, Inspections, and Ledgers

### 6. Run the Development Server
```bash
php artisan serve
```
Visit `http://127.0.0.1:8000` in your web browser.
