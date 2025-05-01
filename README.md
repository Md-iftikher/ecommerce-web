# 🛒 E-Commerce Platform

## 🧩 Project Overview  
This is a comprehensive e-commerce platform designed to deliver a smooth and intuitive shopping experience for users and a powerful admin interface for site managers. It includes a fully functional customer-facing storefront and an admin dashboard for managing inventory, orders, users, and site content.

## 💻 Tech Stack  
- **Backend:** PHP  
- **Database:** MySQL / MariaDB  
- **Frontend:** HTML, CSS, JavaScript  
- **UI Frameworks:** Tailwind CSS + DaisyUI  
- **Icons:** FontAwesome  
- **Interactivity:** AJAX for seamless user experience



---
## 🖼️ Project Preview
## 📄 Full Project Documentation

[View Project Documentation (PDF)](/Project%20report%20CSE311.pdf)

---

## 📁 Project Structure

### 🔸 Client (User Interface)
- `Client/index.php` – Entry point for customer interactions.
- `Client/Pages/` – Product listings, details, cart, login/signup, about us, profile, etc.
- `Client/php/` – Backend logic for product fetch, authentication, cart operations, and user profile management.
- `Client/assets/Images/` – Product/media assets.
- `Client/js/` – Scripts for handling UI interactions (e.g., toast notifications, cart logic).
- `Client/Styles/` – Additional custom styling files.
- `Client/Component/` – Reusable UI components like navbar and footer.

### 🔸 Admin (Management Panel)
- `Admin/index.php` – Admin login gateway.
- `Admin/src/pages/` – Admin pages: dashboard, products, customers, orders, etc.
- `Admin/src/PHP/` – Admin backend logic: authentication, order processing, product CRUD, etc.
- `Admin/src/css/` – Styling for admin UI.
- `Admin/src/js/` – Scripts powering dynamic admin interactions.

### 🔸 Database
- `sql/ecommerce.sql` – Schema definition and sample seed data.
- Structured to include:
  - `admins` – Site admin credentials.
  - `customers` – End-user accounts and contact info.
  - `categories`, `products` – Catalog data.
  - `carts`, `cart_items` – Temporary user shopping data.
  - `orders`, `order_items` – Finalized purchases.
  - `delivery_addresses` – Shipping details linked to users.

---

## 🗃️ Database Schema Highlights  
| Table              | Purpose                                                  |
|-------------------|----------------------------------------------------------|
| `admins`          | Stores hashed login credentials for admins               |
| `customers`       | Contains customer info with secure password storage      |
| `products`        | Holds product name, price, image, description, stock     |
| `categories`      | Organizes products into logical groups                   |
| `carts`, `cart_items` | Handles customer shopping carts                     |
| `orders`, `order_items` | Stores confirmed orders and associated items     |
| `delivery_addresses` | Links orders to customer shipping details            |

---

## ⚙️ Setup Guide

1. Install a PHP + MySQL environment (e.g., [XAMPP](https://www.apachefriends.org/)).
2. Import the database:
   - Open phpMyAdmin or use terminal to import `sql/ecommerce.sql`.
3. Configure DB credentials:
   - Edit `Client/php/config.php` and `Admin/src/PHP/config.php`.
4. Place the project in your server root (e.g., `htdocs/ecommerce-frontend`).
5. Access URLs:
   - **Client site:** [http://localhost/ecommerce-frontend/Client/index.php](http://localhost/ecommerce-frontend/Client/index.php)
   - **Admin panel:** [http://localhost/ecommerce-frontend/Admin/index.php](http://localhost/ecommerce-frontend/Admin/index.php)
6. Log in using credentials from the `admins` table.

---

## ✨ Core Features

### Customer-Facing
- User registration, login, and profile management
- Product browsing with filters and search
- Interactive cart with live updates (add/remove/update)
- Seamless checkout and order placement
- Order tracking system

### Admin Panel
- Dashboard with key metrics
- Add/edit/delete products and categories
- View and manage customers and their orders
- Order processing and fulfillment tools

### UX/UI
- Fully responsive (mobile-first) layout
- Tailwind CSS + DaisyUI for modern design
- FontAwesome icons
- AJAX-powered components for dynamic user experience
- Toast and alert notifications for user feedback

---

## 📌 Extras
- Passwords stored using hashing algorithms for security.
- Includes a detailed [ER diagram](/er%20diagram/ecommerce_er_diagram.pdf) for visualizing relationships between tables.

---



*Crafted with purpose and precision for a real-world eCommerce experience.*
