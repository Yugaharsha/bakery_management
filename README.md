
# 🍰 Bakery Management System

Welcome to the **Bakery Management System** repository!
This project is designed to manage bakery operations including products, orders, customers, sales reports, and admin settings.
It features a **bakery-themed UI** with a **PHP backend** and **MySQL database** for smooth operation and easy management.

---

## Table of Contents

* [Introduction](#introduction)
* [Features](#features)
* [Technologies Used](#technologies-used)
* [Installation](#installation)
* [Usage](#usage)
* [Database Schema](#database-schema)
* [License](#license)

---

## Introduction

The **Bakery Management System** is a web-based application that streamlines bakery operations.
The system has **two primary roles**:

* **Admin:** Manages products, orders, reports, and customers.
* **Customer:** Browses products, places orders, and submits feedback.

---

## Features

###  Admin

* Admin Dashboard with sales, orders, and inventory overview.
* Manage Products:

  * Add, edit, delete products with images.
  * Manage stock levels.
  * View low-stock alerts.
* Manage Orders:

  * View and update order statuses.
  * Track recent orders.
* Manage Customers:

  * View registered customers.
  * Access customer feedback.
* Generate Sales Reports.
* Store Settings and Profile Management.

###  Customer

* Browse bakery products with images and prices.
* Add products to cart and checkout.
* Update personal profile.
* Submit reviews and feedback to the bakery.

---

## Technologies Used

* **Frontend**: HTML, CSS, JavaScript
* **Backend**: PHP (Core PHP)
* **Database**: MySQL (via XAMPP)
* **Server**: Apache

---

## Installation

To set up this project locally, follow these steps:

1. **Clone the repository:**

   ```bash
   git clone https://github.com/yourusername/bakery-management-system.git
   ```

2. **Move the project to XAMPP's htdocs folder:**

   ```bash
   mv bakery-management-system /xampp/htdocs/
   ```

3. **Set up the database:**

   * Open **phpMyAdmin**.
   * Create a database named:

     ```
     bakery_db
     ```
   * Import the `bakery_db.sql` file from the `database` folder.

4. **Configure database connection:**

   * Open `db.php` and update with your credentials:

     ```php
     $servername = "localhost";
     $username   = "root";
     $password   = "";
     $dbname     = "bakery_db";
     ```

5. **Start the server:**

   * Open XAMPP and start **Apache** & **MySQL**.

6. **Access the application:**

   * Go to:

     ```
     http://localhost/bakery-management-system/login_page/Login_Page.php
     ```

---

## Usage

**Admin Login**

1. Navigate to the login page.
2. Enter admin credentials to access the dashboard.
3. Manage products, orders, customers, reports, and settings.

**Customer Login**

1. Navigate to the login page.
2. Log in as a customer.
3. Browse products, add to cart, checkout, and leave feedback.

---

## Database Schema

The database schema includes tables for:

* **users**: Stores admin and customer details.
* **products**: Stores product information including name, price, stock, and image.
* **orders**: Stores order details and statuses.
* **order\_items**: Tracks products within each order.
* **feedback**: Stores customer reviews and messages.
* **sales**: Stores sales transaction records.

For more details, refer to the SQL script in the `database` folder or view the ER diagram provided.

---

## License

© 2025 Harsha Vardhini K. All rights reserved.

Permission is granted to use this software for **personal, non-commercial** purposes only.
Redistribution, modification, or commercial use is prohibited without prior approval from the author.

For commercial inquiries, please contact:
📧 **Harsha Vardhini K** – [harshavardhini1122@gmail.com](mailto:harshavardhini1122@gmail.com)

---
