# Smart Healthcare Management System (SHMS)

A modern, role-based healthcare management system built with PHP, PDO, MySQL, and Materialize CSS. Designed for streamlined patient care, appointment scheduling, and medical record management.

## Features
- **Role-Based Access:** Patient, Doctor, Receptionist, and Admin portals
- **Appointment Management:** Online booking, status tracking, and admin approval
- **Department & Doctor Directory:** Browse specialties, view profiles, and check consultation fees
- **Prescription System:** Doctors can issue and manage digital prescriptions
- **User Management:** Secure registration, login, and session handling
- **Responsive UI:** Mobile-friendly design with a professional matte healthcare theme

## Tech Stack
- **Backend:** PHP 8+, PDO (MySQL)
- **Frontend:** HTML5, CSS3, JavaScript (jQuery), Materialize CSS
- **Database:** MySQL / MariaDB
- **Server:** Apache (XAMPP/WAMP/LAMP)

## Installation & Setup
1. Clone or download this repository into your `htdocs` folder (e.g., `C:\xampp\htdocs\SHMS`)
2. Start Apache and MySQL via XAMPP control panel
3. Open phpMyAdmin (`http://localhost/phpmyadmin`)
4. Create a new database named `shms`
5. Import the `shms.sql` file into the `shms` database
6. Update database credentials in `backends/config.php` if necessary
7. Access the application at `http://localhost/SHMS`

## Default Credentials
- **Admin Panel:** `http://localhost/SHMS/admin/login-admin.php`
  - Email: `admin@shms.com`
  - Password: `12345`
- **User Accounts:** Register via the frontend "Register" modal. Patient is the default role set. Doctors need to be registered and manually added via admin since doctors need to ensure proper valid document submission to the rightful authority before getting access to SHMS.

## Database Schema Overview
- `users`: Stores patient and doctor accounts with role enumeration
- `admin`: System administrator credentials
- `departments`: Medical departments that are available
- `doctors`: Doctor profiles linked to departments
- `appointments`: Booking records with status tracking (pending/confirmed/completed/cancelled)
- `prescriptions`: Digital prescriptions issued by doctors to patients
