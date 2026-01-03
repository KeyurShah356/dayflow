# Dayflow HRMS - Quick Start Guide

## 🚀 How to Run the System

### Step 1: Install XAMPP (if not already installed)
1. Download XAMPP from: https://www.apachefriends.org/
2. Install XAMPP to `C:\xampp` (default location)

### Step 2: Copy Project Files
1. Copy the entire `dayflow` folder (or rename `hrms` to `dayflow`)
2. Place it in: `C:\xampp\htdocs\dayflow`
   - Full path should be: `C:\xampp\htdocs\dayflow\`

### Step 3: Start XAMPP Services
1. Open **XAMPP Control Panel**
2. Click **Start** for **Apache**
3. Click **Start** for **MySQL**
   - Both should show green/running status

### Step 4: Create Database
1. Open your browser
2. Go to: **http://localhost/phpmyadmin**
3. Click **New** in the left sidebar
4. Database name: `dayflow_hrms`
5. Collation: `utf8mb4_general_ci` (or leave default)
6. Click **Create**

### Step 5: Import Database Schema
1. In phpMyAdmin, click on `dayflow_hrms` database (left sidebar)
2. Click **Import** tab (top menu)
3. Click **Choose File**
4. Navigate to: `C:\xampp\htdocs\dayflow\database\schema.sql`
5. Click **Go** button (bottom right)
6. You should see "Import has been successfully finished"

### Step 6: Create Admin User
**Option A: Via Browser (Easiest)**
1. Open browser
2. Go to: **http://localhost/dayflow/setup/create_admin.php**
3. You should see: "Admin user created successfully!"
4. Note the credentials displayed

**Option B: Via Command Line**
1. Open Command Prompt (cmd)
2. Navigate to project:
   ```
   cd C:\xampp\htdocs\dayflow
   ```
3. Run:
   ```
   C:\xampp\php\php.exe setup\create_admin.php
   ```

### Step 7: Access the Application
1. Open browser
2. Go to: **http://localhost/dayflow**
   - Or directly: **http://localhost/dayflow/auth/login.php**

### Step 8: Login
**Admin Login:**
- Email: `admin@dayflow.com`
- Password: `admin123`

Click **Login** button

---

## 📋 Quick Checklist

- [ ] XAMPP installed
- [ ] Project copied to `C:\xampp\htdocs\dayflow`
- [ ] Apache started in XAMPP
- [ ] MySQL started in XAMPP
- [ ] Database `dayflow_hrms` created in phpMyAdmin
- [ ] Schema imported successfully
- [ ] Admin user created (via setup script)
- [ ] Application accessed at http://localhost/dayflow
- [ ] Successfully logged in as admin

---

## 🔧 Troubleshooting

### Issue: "Connection failed" error
**Solution:**
- Check MySQL is running in XAMPP Control Panel
- Verify database name is `dayflow_hrms`
- Check `config/db.php` - default credentials are:
  - User: `root`
  - Password: `` (empty)
  - If your MySQL has a password, update `config/db.php`

### Issue: Page shows "404 Not Found"
**Solution:**
- Verify project is in: `C:\xampp\htdocs\dayflow`
- Check Apache is running
- Try: http://localhost/dayflow/auth/login.php

### Issue: Admin login doesn't work
**Solution:**
- Run setup script again: http://localhost/dayflow/setup/create_admin.php
- Or register a new account: http://localhost/dayflow/auth/register.php

### Issue: Database import fails
**Solution:**
- Make sure you selected the `dayflow_hrms` database first
- Check file path to schema.sql is correct
- Try copying the SQL content and pasting in SQL tab

### Issue: "Access denied" for database
**Solution:**
- Default MySQL root user has no password
- If you set a password, update `config/db.php`:
  ```php
  define('DB_PASS', 'your_password');
  ```

---

## 🎯 What to Do Next

1. **Login as Admin**
   - View dashboard
   - Check statistics

2. **Register Employee Account**
   - Go to: http://localhost/dayflow/auth/register.php
   - Fill the registration form
   - Role: Select "Employee"
   - Login with new credentials

3. **Test Employee Features**
   - Check-in for attendance
   - Apply for leave
   - View profile

4. **Test Admin Features**
   - View all employees
   - Approve/reject leaves
   - Update employee salaries
   - View attendance records

---

## 📁 Important URLs

- **Login:** http://localhost/dayflow/auth/login.php
- **Register:** http://localhost/dayflow/auth/register.php
- **Admin Dashboard:** http://localhost/dayflow/admin/dashboard.php
- **Employee Dashboard:** http://localhost/dayflow/employee/dashboard.php
- **Setup Admin:** http://localhost/dayflow/setup/create_admin.php
- **phpMyAdmin:** http://localhost/phpmyadmin

---

## ✅ Success Indicators

You'll know it's working when:
- ✅ You can access http://localhost/dayflow without errors
- ✅ Login page loads and displays properly
- ✅ You can login with admin credentials
- ✅ Dashboard shows statistics
- ✅ No PHP errors appear on pages

---

**That's it! Your HRMS system is now running! 🎉**

