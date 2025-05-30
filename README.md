# LlaqeNDCApp
Laravel Drug Search App, with Breeze for Authentication, Livewire, and Profile Funcionality

Apologies for the confusion earlier! Here's the **entire README** in a single block of text, formatted properly in **Markdown**:

````markdown
# Drug Search Application Setup

This README provides instructions to set up the **Drug Search Application** on a Linux environment using **Laravel**, **MySQL**, **Node.js**, **Livewire**, and **Laravel Breeze**.

---

## Test UDC Numbers

Here are some sample UDC (Unique Drug Code) numbers for testing:

- 0573-0134
- 69618-011
- 65162-637
- 0573-0134, 69618-011, 65162-637

---

## My Setup

### Install Required Packages

Start by installing the necessary packages on your Linux system:

```bash
sudo apt update
sudo apt install php php-cli php-mbstring php-xml php-curl php-mysql php-zip unzip curl
````

### Install Composer

Next, install Composer (PHP package manager):

```bash
cd ~
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php
sudo mv composer.phar /usr/local/bin/composer
composer --version
```

### Install MySQL

Install MySQL server:

```bash
sudo apt install mysql-server
```

### Install Node.js and npm

Install Node.js and npm to handle the front-end dependencies:

```bash
sudo apt install nodejs npm
node -v
npm -v
```

---

## Setup Steps

### 1. Install Laravel

Create a new Laravel project:

```bash
composer create-project laravel/laravel drug-search
```

### 2. Start and Enable MySQL

Ensure MySQL service is running and enabled:

```bash
sudo systemctl start mysql
sudo systemctl enable mysql
```

Log in to MySQL:

```bash
sudo mysql -u root -p
```

### 3. Create Database and User

Inside MySQL, create the database and a new user with the necessary privileges:

```sql
CREATE DATABASE llaqe CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'larauser'@'localhost' IDENTIFIED BY 'password123';
GRANT ALL PRIVILEGES ON llaqe.* TO 'larauser'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### 4. Update `.env` File

In your Laravel project, open the `.env` file and update the database section with your credentials:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=llaqe
DB_USERNAME=larauser
DB_PASSWORD=password123
```

### 5. Run Migrations

Run the migrations to set up your database structure:

```bash
php artisan migrate
```

---

## Laravel Breeze & Livewire UI

### 6. Install Laravel Breeze and Livewire

To set up authentication and dynamic UI components, first install **Laravel Breeze** and **Livewire**:

```bash
composer require laravel/breeze --dev
composer require livewire/livewire
```

Next, install the front-end dependencies and compile assets using Vite:

```bash
npm install && npm run dev
```

Then, serve the Laravel application:

```bash
php artisan serve
```

### 7. Install Breeze Authentication

To enable authentication, run the following command:

```bash
php artisan breeze:install livewire
npm install && npm run build
php artisan migrate
```

---

### 8. Create Livewire Component

Generate a Livewire component for dynamic drug search functionality:

```bash
php artisan make:livewire DrugSearch
```

This will create both the UI, logic, and view for the drug search.

---

### 9. Additional Setup (If Using Repository)

If you cloned this project from the repository, run the following commands to ensure everything is up to date:

```bash
npm install && npm run build
php artisan migrate
```



