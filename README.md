# 🍷 Crimson Cellar

**Crimson Cellar** is a fully functional, PHP-based e-commerce web application designed for selling premium red wines.

This project was developed from scratch to demonstrate core full-stack web development concepts, including **Object-Oriented Programming (OOP)**, secure session management, and MySQL database integration, without relying on high-level frameworks like Laravel or Symfony.

![License](https://img.shields.io/badge/license-MIT-blue.svg) ![PHP](https://img.shields.io/badge/PHP-7.4%2B-purple) ![MySQL](https://img.shields.io/badge/MySQL-Database-orange)

## 🌟 Key Features

### 💻 Technical Architecture
- **OOP Cart System:** Implements custom `Product` and `Cart` classes (`product_cart_class.php`) to encapsulate business logic and state management.
- **Security Best Practices:**
  - **Payment Tokenization Simulation:** Credit card inputs are processed via a simulated secure gateway; sensitive data (PAN/CVV) is **never** stored in the database.
  - **Input Validation:** Rigorous sanitization of user inputs to prevent SQL injection.
  - **Access Control:** Session-based authentication protecting member-only pages.

### 🛍️ E-Commerce Functionality
- **Product Catalog:** Dynamic fetching of wine details, images, and stock levels (`shop.php`).
- **Shopping Cart:** Add, remove, and update quantities dynamically using PHP Sessions.
- **Order Processing:** Complete checkout flow that records transaction history.
- **Search:** Functional search bar to filter products by name or criteria.

### 👤 User System
- **Authentication:** Registration and Login system.
- **Member Dashboard:** Users can view order history and manage profiles.
- **Admin Ready:** Database schema supports role-based extensions.

## 🛠️ Technology Stack

- **Backend:** Native PHP (Procedural & OOP)
- **Database:** MySQL
- **Frontend:** HTML5, CSS3, JavaScript (Vanilla)
- **Server Environment:** Apache (via XAMPP/WAMP)

## 🚀 Installation & Setup

Follow these steps to run the project locally:

1.  **Prerequisites:**
    - Install **XAMPP** (or any environment with Apache & MySQL).

2.  **Database Setup:**
    - Open **phpMyAdmin** (`http://localhost/phpmyadmin`).
    - Create a new database named **`crimsondb`**.
    - Import the `crimson_cellar.sql` file located in the root/sql folder of this repository.

3.  **Configuration:**
    - Open `conn_db.php`.
    - Check the database credentials. Default XAMPP settings are usually:
      ```php
      $servername = "localhost";
      $username = "root";
      $password = ""; // Empty for XAMPP
      $dbname = "crimsondb";
      ```

4.  **Run:**
    - Clone or copy this project folder into your server's root directory (e.g., `C:\xampp\htdocs\crimson-cellar`).
    - Start **Apache** and **MySQL**.
    - Visit `http://localhost/crimson-cellar/index.php` in your browser.

## 🧪 Demo Account

To test the member features (Checkout, Order History) without registering, use the pre-configured admin/test account:

- **Username (Email):** `john.smith@email.com`
- **Password:** `hashpassword1`

## ⚠️ Security Disclaimer

**This project is for educational and portfolio purposes only.**

- **Payment Safety:** The checkout process includes a **simulated** credit card form. It demonstrates data handling (masking/validation) but does **NOT** process real financial transactions. Do not enter real credit card numbers.
- **Data Storage:** The application strictly avoids storing sensitive payment information (PCI-DSS compliance simulation).

## 📄 License

This project is open-sourced under the **MIT License**.

Copyright (c) 2024-2026 [Your Name]

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit users of the Software to do so, subject to
the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.