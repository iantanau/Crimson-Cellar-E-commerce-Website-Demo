# Crimson Cellar

**Crimson Cellar** is a fully functional, PHP-based e-commerce web application designed for selling premium wines. It demonstrates core full-stack web development concepts without relying on a high-level framework:

- Object-oriented cart logic (`Product` and `Cart` classes)
- Session-based authentication and access control
- MySQL database integration
- Sanitized user input and simulated payment tokenization

## Tech stack

- Backend: native PHP (procedural and OOP)
- Database: MySQL
- Frontend: HTML5, CSS3, vanilla JavaScript
- Local server: Apache via XAMPP/WAMP, or PHP's built-in server
- Hosting target: Vercel with the `vercel-php` community runtime

## Project structure

```
api/          PHP page handlers used by Vercel Functions
src/          Shared PHP includes, classes, config and templates
public/       Static assets (CSS and product images)
database/     MySQL schema/seed file
vercel.json   Vercel deployment configuration
```

The old flat layout has been reorganised so the repository root only contains top-level configuration and documentation.

## Local setup

### Prerequisites

- PHP 7.4 or newer with `mysqli`
- MySQL/MariaDB

### 1. Create the database

Create a database named `crimsondb` and import `database/crimson_cellar.sql`.

### 2. Configure database access

`src/conn_db.php` reads connection settings from environment variables and falls back to XAMPP-style defaults:

```text
MYSQL_HOST=localhost
MYSQL_USER=root
MYSQL_PASSWORD=
MYSQL_DATABASE=crimsondb
MYSQL_PORT=3307
```

If your local MySQL uses a different port or password, set the environment variables before starting the server, or edit the defaults in `src/conn_db.php`.

### 3. Run with PHP's built-in server

From the project root:

```bash
php -S localhost:8000 -t public scripts/router.php
```

Open `http://localhost:8000` in your browser.

The router maps URL paths such as `/shop.php` to their corresponding handler in `api/`, while `public/` serves CSS and images.

## Deploying to Vercel

Vercel does not run PHP by default, so this project uses the [vercel-php](https://github.com/vercel-community/php) community runtime declared in `vercel.json`. Static files are served from `public/`, and dynamic requests are rewritten to PHP handlers in `api/`.

### 1. Push the branch

```bash
git push origin deploy/vercel
```

### 2. Import the repository in Vercel

In the Vercel dashboard, add a new project, select this repository, and choose the `deploy/vercel` branch. Vercel should detect `vercel.json` automatically.

### 3. Add environment variables

Set these in **Project Settings > Environment Variables**:

| Name | Example value |
| --- | --- |
| `MYSQL_HOST` | `your-database-host` |
| `MYSQL_USER` | `your-database-user` |
| `MYSQL_PASSWORD` | `your-database-password` |
| `MYSQL_DATABASE` | `crimsondb` |
| `MYSQL_PORT` | `3306` |

MySQL is not provided by Vercel itself. Host the database separately (for example Railway, Aiven, AWS RDS, or another managed MySQL provider) and import `database/crimson_cellar.sql` there.

### 4. Deploy

Deploy the project. The site root will be served through `api/index.php`, and pages such as `/shop.php`, `/login.php`, and `/cart.php` are rewritten to their matching `api/` handlers.

## Demo account

To test member features (checkout and order history) without registering:

- Username (email): `john.smith@email.com`
- Password: `hashpassword1`

## Important Vercel limitations

- PHP sessions are stored on the local function filesystem by default, which is not durable across cold starts or multiple instances. For production, configure a Redis-backed session handler or use a marketplace Redis integration.
- Vercel Functions are stateless. Keep file uploads and user-generated files in external storage such as Vercel Blob.
- Database credentials must be configured as Vercel environment variables, never committed to the repository.

## Security disclaimer

This project is for educational and portfolio purposes only. The checkout process is a simulated credit card form and does **not** process real financial transactions. Do not enter real credit card numbers. Sensitive payment details are never written to the database.

## License

This project is open-sourced under the MIT License.
