# Dayflow HRMS - Quick Setup Guide

## Step-by-Step Setup

### 1. Copy Project to XAMPP
- Copy the entire `dayflow` folder to `C:\xampp\htdocs\`
- Your project should be at: `C:\xampp\htdocs\dayflow\`

### 2. Start XAMPP Services
- Open XAMPP Control Panel
- Start **Apache**
- Start **MySQL**

### 3. Create Database
- Open browser: http://localhost/phpmyadmin
- Click **New** in the left sidebar
- Database name: `dayflow_hrms`
- Collation: `utf8mb4_general_ci`
- Click **Create**

### 4. Import Schema
- Select `dayflow_hrms` database in phpMyAdmin
- Click **Import** tab
- Click **Choose File**
- Select: `database/schema.sql`
- Click **Go** at the bottom

### 5. Create Admin User
**Option A: Via Browser (Recommended)**
- Open: http://localhost/dayflow/setup/create_admin.php
- You should see: "Admin user created successfully!"
- Credentials:
  - Email: `admin@dayflow.com`
  - Password: `admin123`

**Option B: Via Command Line**
- Open command prompt
- Navigate to project folder: `cd C:\xampp\htdocs\dayflow`
- Run: `php setup/create_admin.php`

### 6. Access Application
- Open browser: http://localhost/dayflow
- Or directly: http://localhost/dayflow/auth/login.php
- Login with admin credentials:
  - Email: `admin@dayflow.com`
  - Password: `admin123`

## Troubleshooting

### Database Connection Error
- Ensure MySQL is running in XAMPP
- Check `config/db.php` credentials (default: root, no password)
- Verify database `dayflow_hrms` exists

### Page Not Found (404)
- Verify project is in `htdocs/dayflow`
- Check Apache is running
- Try: http://localhost/dayflow/auth/login.php

### Admin Login Not Working
- Run setup script again: http://localhost/dayflow/setup/create_admin.php
- Or register a new admin account via registration page

### Session Issues
- Ensure PHP sessions are enabled
- Check file permissions on the project folder

## Next Steps

1. **Login as Admin**
   - Manage employees
   - View attendance
   - Approve/reject leaves
   - Update salaries

2. **Register Employees**
   - Go to: http://localhost/dayflow/auth/register.php
   - Create employee accounts
   - Employees can then login and use the system

3. **Test Features**
   - Employee: Check-in/out, apply for leave, view profile
   - Admin: Manage employees, approve leaves, update payroll

## Project Structure

```
dayflow/
├── admin/          # Admin dashboard and features
├── auth/           # Login, register, logout
├── config/         # Database configuration
├── database/       # SQL schema file
├── employee/       # Employee dashboard and features
├── includes/       # Common header, footer, auth check
├── assets/         # CSS stylesheet
├── setup/          # Setup scripts
└── index.php       # Landing page
```

## Default Credentials

**Admin:**
- Email: admin@dayflow.com
- Password: admin123

**Note:** Create additional users via registration page.

## Support

If you encounter any issues:
1. Check XAMPP services are running
2. Verify database is created and schema imported
3. Check browser console for errors
4. Verify file permissions

Happy coding! 🚀

