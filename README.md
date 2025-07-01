# CodeIgniter4-PHP8 App

A web application built with **CodeIgniter 4** and **PHP 8**, featuring modules for departments, designations, employees, payroll, payslips, and users.

---

## 🛠 Prerequisites

- PHP 8.0 or newer
- Composer
- MySQL or compatible database
- Apache or Nginx
- CodeIgniter 4 framework

---

## 🚀 Steps to Run in Your Local Development

1. Clone the repository:
   ```bash
   git clone https://github.com/yourusername/CodeIgniter4-PHP8.git
   cd CodeIgniter4-PHP8
2. Copy the `.env` file if not present:
   ```bash
   cp env .env
3. Open `.env` and update:
   
   - `KEYCLOAK_BASE_URL`
   - `KEYCLOAK_REALM`
   - `KEYCLOAK_CLIENT_ID`
   - `KEYCLOAK_CLIENT_SECRET`
     
5. Install dependencies:
   ```bash
   composer install
6. Start the built-in development server:
   ```bash
   php spark serve
7. Visit your app:
   ```bash
   http://localhost:8080
