# Dayflow HRMS - Human Resource Management System

A complete HRMS system built with Plain PHP, MySQL, HTML, CSS, and minimal JavaScript.

## Features

### Authentication & Authorization
- User registration with Employee ID, Name, Email, Password, and Role
- Secure login with password hashing
- Session-based authentication
- Role-based access control (Admin/Employee)

### Employee Features
- Dashboard with attendance and leave overview
- View and edit profile (contact information)
- Check-in/Check-out attendance tracking
- Apply for leave (Paid, Sick, Unpaid)
- View leave status and history
- View salary information (read-only)

### Admin/HR Features
- Dashboard with statistics
- View and manage all employees
- Edit employee details
- View all attendance records
- Approve/Reject leave requests
- Manage payroll and update salaries

## Installation

### Prerequisites
- XAMPP (Apache + MySQL + PHP 8+)
- Web browser

### Setup Instructions

1. **Extract Project**
   - Extract the project folder to `C:\xampp\htdocs\dayflow`
   - Or your XAMPP htdocs directory

2. **Database Setup**
   - Start XAMPP Control Panel
   - Start Apache and MySQL services
   - Open phpMyAdmin (http://localhost/phpmyadmin)
   - Import the database schema:
     - Go to phpMyAdmin
     - Click "New" to create a database (or skip if auto-created)
     - Select "dayflow_hrms" database
     - Click "Import" tab
     - Choose file: `database/schema.sql`
     - Click "Go"
   - **Create Admin User:**
     - After importing schema, run the setup script to create admin user:
     - Open: http://localhost/dayflow/setup/create_admin.php
     - Or run via command line: `php setup/create_admin.php` (from project root)

3. **Database Configuration**
   - Open `config/db.php`
   - Update database credentials if needed (default: root, no password)

4. **Access the Application**
   - Open browser: http://localhost/dayflow
   - Or: http://localhost/dayflow/auth/login.php

## Default Login Credentials

**Admin Account:**
- Email: admin@dayflow.com
- Password: admin123

**Note:** You can register new accounts through the registration page.

## Project Structure

```
/dayflow
  /config
    db.php                 # Database connection
  /auth
    login.php              # Login page
    register.php           # Registration page
    logout.php             # Logout handler
  /admin
    dashboard.php          # Admin dashboard
    employees.php          # Employee management
    attendance.php         # View all attendance
    leaves.php             # Leave request management
    payroll.php            # Salary management
  /employee
    dashboard.php          # Employee dashboard
    profile.php            # View/edit profile
    attendance.php         # Check-in/out, view attendance
    leave.php              # Apply for leave
    payroll.php            # View salary
  /includes
    header.php             # Common header
    footer.php             # Common footer
    auth_check.php         # Authentication check
  /assets
    style.css              # Stylesheet
  /database
    schema.sql             # Database schema
  index.php                # Landing page (redirects)
```

## Security Features

- Password hashing using `password_hash()` and `password_verify()`
- SQL injection prevention using prepared statements
- Session-based authentication
- Role-based access control
- Input validation and sanitization

## Database Schema

The system uses the following main tables:
- `users` - User accounts and authentication
- `employee_profiles` - Employee profile information
- `attendance` - Attendance records
- `leaves` - Leave requests and approvals
- `payroll` - Salary information

## Technology Stack

- **Backend:** PHP 8+ (Plain PHP, no frameworks)
- **Database:** MySQL
- **Frontend:** HTML5, CSS3, minimal JavaScript
- **Server:** Apache (XAMPP)
- **Authentication:** PHP Sessions

## Notes

- All code is original and written from scratch
- No external frameworks or libraries used
- Simple, readable, and explainable code
- Suitable for demo and presentation
- Password for default admin: `admin123` (stored as hash in database)

## Troubleshooting

1. **Database connection error:**
   - Check MySQL service is running in XAMPP
   - Verify database credentials in `config/db.php`
   - Ensure database `dayflow_hrms` exists

2. **Page not found:**
   - Verify project is in correct directory (htdocs/dayflow)
   - Check Apache service is running
   - Verify URL path matches your setup

3. **Session issues:**
   - Ensure PHP sessions are enabled
   - Check file permissions

## License

This project is built for educational/demo purposes.

