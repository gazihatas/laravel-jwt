<p align="center">
    <a href="https://laravel.com" target="_blank">
        <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
    </a>
</p>



# JWT Authentication System

This project implements JWT (JSON Web Token) authentication using Laravel. It provides a secure authentication system for API-based applications, allowing users to register, log in, and access protected routes.

## Table of Contents
1. [Installation](#installation)
2. [Docker Installation](#running-the-project-with-docker)

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
<br>
<br>
<br>

<p align="center">
    <img src="img.png" alt="img.png" />
</p>

## Running the Project with Docker

### 1. Install Required Tools:
Ensure that you have [Docker](https://www.docker.com/) and [Docker Compose](https://docs.docker.com/compose/install/) installed on your machine.

### 2. Create the `.env` File:
Copy the `.env.example` file to `.env` and configure your database credentials:
   ```bash
   cp .env.example .env
   ```

### 3. Generate SSL Certificates:
To enable SSL for your local environment, generate certificates using `mkcert`:
   ```bash
   mkcert -key-file nginx/ssl/api.dev.com.key -cert-file nginx/ssl/api.dev.com.crt api.dev.com 
   ```

### 4. Adding a Domain to the Hosts File:

#### macOS and Linux:
1. Open the terminal and run the following command:
   ```bash
   sudo nano /etc/hosts
   ```   
2. Add the following line at the end of the file:
   ```bash
   127.0.0.1   api.dev.com
   ```
3. Save and exit (CTRL + O, ENTER, CTRL + X).

#### Windows:
1. Open the hosts file with a text editor as **administrator**:
   ```bash
   C:\Windows\System32\drivers\etc\hosts
   ```
2. Add the following line at the end of the file:
   ```bash
   127.0.0.1   api.dev.com
   ```
3. Save and close the file.
### 5. Start Services with Docker Compose:
Build and run the containers by executing the following command:
   ```bash
   docker-compose up -d --build
   ```

### 6. Run Database Migrations:
After the services are up, run the migrations to set up the database tables:
   ```bash
   docker exec -it laravel_api php artisan migrate
   ```

### 7. Generate JWT Secret Key:
Generate the JWT secret key for authentication:
   ```bash
   docker exec -it laravel_api php artisan jwt:secret
   ```

### 8. Managing Laravel Horizon:
If you're using Laravel Horizon for queue management, you can start it by running:
   ```bash
   docker exec -it laravel_horizon php artisan horizon
   ```

### 9. Managing the Database and Services in Docker:
You can manage the database and other services using Docker Compose commands:
   ```bash
   docker-compose stop     # Stop the running services
   docker-compose down     # Stop and remove all containers and volumes
   ```

By following these steps, you will be able to successfully run the JWT Authentication project in Docker.
