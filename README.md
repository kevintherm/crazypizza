# Crazy Pizza

Crazy Pizza is a web application for a pizza restaurant that allows users to browse the menu, customize their pizzas, and place orders. It features a dynamic shopping cart, order tracking, and an admin dashboard for managing products and orders.
You can copy this repo and modify it to your needs with no attribution required.

## Features

* **Pizza Customization:** Users can add extra toppings and specify the size of their pizza.
* **Shopping Cart:** A persistent shopping cart that stores items for unregistered users.
* **Order Placement:** Users can place orders and pay through Stripe integration.
* **Order Tracking:** Users can track the status of their orders using an invoice number.
* **Admin Dashboard:** An admin dashboard for managing pizzas, ingredients, orders, and coupons.
* **User Authentication:** An authentication system for admin users.

## Technologies Used

* **Backend:** Laravel, PHP
* **Frontend:** Blade, Alpine.js, Tailwind CSS, Vite
* **Database:** MySQL (default), SQLite, PostgreSQL, SQL Server
* **Payment Gateway:** Stripe

## Installation

1.  **Clone the repository:**

    ```bash
    git clone [https://github.com/crazypizza/crazypizza.git](https://github.com/crazypizza/crazypizza.git)
    ```

2.  **Install dependencies:**

    ```bash
    composer install
    npm install
    ```

3.  **Create a copy of the `.env.example` file and name it `.env`:**

    ```bash
    cp .env.example .env
    ```

4.  **Generate an application key:**

    ```bash
    php artisan key:generate
    ```

5.  **Configure your database in the `.env` file.**

6.  **Run the database migrations:**

    ```bash
    php artisan migrate
    ```

7.  **Seed the database with initial data:**

    ```bash
    php artisan db:seed
    ```

8.  **Build the frontend assets:**

    ```bash
    npm run build
    ```

9.  **Run the development server:**

    ```bash
    php artisan serve
    ```

## Usage

* The application will be available at `http://localhost:8000`.
* The admin dashboard can be accessed at `/dashboard`.
* The default admin credentials are:
    * **Email:** admin@test.com
    * **Password:** 123
