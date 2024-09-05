<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>



# JWT Authentication System

This project implements JWT (JSON Web Token) authentication using Laravel. It provides a secure authentication system for API-based applications, allowing users to register, log in, and access protected routes.

## Table of Contents
1. [Installation](#installation)


---

## Installation

1. Clone the repository:
    ```bash
    git clone https://github.com/gazihatas/laravel-jwt.git
    cd laravel-jwt
    ```

2. Install dependencies:
    ```bash
    composer install
    ```
    or
    ```bash
    composer install --ignore-platform-reqs
    ```

3. Copy `.env.example` to `.env` and set your database credentials:
    ```bash
    cp .env.example .env
    ```

4. Generate application key:
    ```bash
    php artisan key:generate
    ```

5. Run migrations to set up the database:
    ```bash
    php artisan migrate
    ```
   
6. Generate JWT secret:
    ```bash
    php artisan jwt:secret
    ```

7. Start the development server:
    ```bash
    php artisan serve
    ```
   
---
