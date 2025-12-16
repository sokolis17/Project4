# PHP MVC Blog

Educational blog engine built with plain PHP 8.3 and PostgreSQL, without frameworks.  
The goal of the project is to understand how authentication, routing and database layer work **under the hood** before moving to modern frameworks.

## Tech stack

- PHP 8.3
- PostgreSQL
- PDO (prepared statements)
- HTML, CSS, Bootstrap 5
- Sessions for auth
- Git (project under version control)

## Features

- User registration
- Login / Logout
- Password hashing (`password_hash` / `password_verify`)
- Change password page
- Blog posts:
  - create a new post
  - assign a category to a post
  - view a single post page (`post.php`)
- Categories listing in forms and on post pages
- Basic access control:
  - creating posts and changing password only for authenticated users
  - protected pages redirect guests to login

> Edit/delete posts and comments are **not implemented yet** – this is an MVP blog focused on auth and basic CRUD.

## Project structure (short)

- `classes/Database.php` – simple DB wrapper returning a PDO connection
- `classes/User.php` – user logic (register, login)
- `includes/init.php` – common bootstrap: session start, autoload includes, DB + User objects
- `includes/header.php`, `includes/footer.php` – layout (navigation, Bootstrap)
- `index.php` – homepage, posts listing
- `login.php` / `register.php` – auth pages
- `create_post.php` – create a new blog post
- `post.php` – single post view
- `change_password.php` – change current user password
- `logout.php` – destroy session and redirect to homepage

## Database schema (PostgreSQL)

Create a database, for example:

```sql
CREATE DATABASE blog_db;

Then create tables (simplified version):

SQL

-- Users
CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    username      VARCHAR(50)  NOT NULL UNIQUE,
    email         VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    is_admin      BOOLEAN      DEFAULT false,
    created_at    TIMESTAMP    DEFAULT CURRENT_TIMESTAMP
);

-- Categories
CREATE TABLE categories (
    id   SERIAL PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE,
    slug VARCHAR(50) NOT NULL UNIQUE
);

-- Posts
CREATE TABLE posts (
    id           SERIAL PRIMARY KEY,
    title        VARCHAR(200) NOT NULL,
    slug         VARCHAR(200) NOT NULL UNIQUE,
    content      TEXT         NOT NULL,
    user_id      INTEGER      NOT NULL REFERENCES users(id),
    category_id  INTEGER      REFERENCES categories(id),
    is_published BOOLEAN      DEFAULT true,
    views_count  INTEGER      DEFAULT 0,
    created_at   TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
    updated_at   TIMESTAMP    DEFAULT CURRENT_TIMESTAMP
);

You can adjust names and columns if your local schema is slightly different.
Configuration

    Copy the project to your local machine.
    Make sure PostgreSQL is running and the blog_db database (or another name) exists.
    Configure DB credentials in classes/Database.php:

PHP

class Database {
    private $host = 'localhost';
    private $db_name = 'blog_db';
    private $username = 'postgres';
    private $password = 'YOUR_PASSWORD';
    private $port = '5432';

    public function getConnection() {
        $dsn = "pgsql:host={$this->host};port={$this->port};dbname={$this->db_name}";
        return new PDO($dsn, $this->username, $this->password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    }
}

How to run

You can use either the built-in PHP server or Apache/Nginx.
Option 1: PHP built-in server (simple)

From the project root:

Bash

php -S localhost:8000

Then open in browser:

text

http://localhost:80

Option 2: Apache (your current setup)

    Place the project in your Apache DocumentRoot (e.g. /var/www/php-mvc-blog).
    Configure a virtual host if needed (e.g. blog.local → /var/www/php-mvc-blog).
    Restart Apache and open the configured host in the browser.

What I practiced in this project

    Designing a relational schema for a blog (users, posts, categories)
    Writing SQL queries with JOIN, GROUP BY and basic aggregations
    Using PDO with prepared statements to prevent SQL injections
    Password hashing and secure authentication flow
    Organizing PHP code with simple OOP (separating DB, user logic and views)
    Using Bootstrap 5 to quickly build a clean UI
