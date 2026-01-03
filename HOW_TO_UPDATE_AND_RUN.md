# How to Update and Run the Code - Complete Guide

## 📋 Prerequisites
- ✅ XAMPP installed
- ✅ Project located at: `C:\xampp\htdocs\dayflow\`
- ✅ Database `dayflow_hrms` already exists

---

## 🔄 Step-by-Step Update Process

### Step 1: Stop XAMPP Services (If Running)
1. Open **XAMPP Control Panel**
2. Click **Stop** for **Apache** (if running)
3. Click **Stop** for **MySQL** (if running)
4. Wait for services to stop completely

---

### Step 2: Backup Your Database (Recommended)
1. Open phpMyAdmin: http://localhost/phpmyadmin
2. Select `dayflow_hrms` database
3. Click **Export** tab
4. Click **Go** to download backup
5. Save the backup file safely

---

### Step 3: Update Database Schema
1. Open phpMyAdmin: http://localhost/phpmyadmin
2. Select `dayflow_hrms` database (left sidebar)
3. Click **SQL** tab (top menu)
4. Copy and paste this SQL code:

```sql
-- Check if columns already exist (optional - won't cause error if run twice)
ALTER TABLE users 
ADD COLUMN IF NOT EXISTS otp_code VARCHAR(6) NULL AFTER verification_token,
ADD COLUMN IF NOT EXISTS otp_expires_at TIMESTAMP NULL AFTER otp_code;

-- Create index if it doesn't exist
CREATE INDEX IF NOT EXISTS idx_otp_code ON users(otp_code);
```

**OR** if your MySQL version doesn't support IF NOT EXISTS, use:

```sql
-- Add OTP columns (safe to run multiple times)
ALTER TABLE users 
ADD COLUMN otp_code VARCHAR(6) NULL AFTER verification_token;

ALTER TABLE users 
ADD COLUMN otp_expires_at TIMESTAMP NULL AFTER otp_code;

-- Create index
CREATE INDEX idx_otp_code ON users(otp_code);
```

5. Click **Go** button
6. ✅ You should see: "2 rows affected" or similar success message

**Note:** If you see "Duplicate column name" error, the columns already exist - that's fine, skip to next step.

---

### Step 4: Verify Updated Files Are in Place
Check that these files exist in your project:

**New Files (should be present):**
- ✅ `config/email.php`
- ✅ `auth/verify_otp.php`

**Modified Files (should have updates):**
- ✅ `auth/register.php`
- ✅ `admin/attendance.php`
- ✅ `admin/payroll.php`
- ✅ `config/db.php`
- ✅ `employee/attendance.php`
- ✅ `employee/payroll.php`
- ✅ `assets/style.css`

**If files are missing:**
- Copy all files from the updated codebase to your project folder
- Ensure file structure matches the original

---

### Step 5: Start XAMPP Services
1. Open **XAMPP Control Panel**
2. Click **Start** for **Apache**
   - Wait until it shows green/running
3. Click **Start** for **MySQL**
   - Wait until it shows green/running

✅ **Check:** Both should show green status

---

### Step 6: Verify Database Connection
1. Open browser: http://localhost/dayflow
2. Try to login (should work normally)
3. If you see database errors, check `config/db.php`:
   - DB_HOST: localhost
   - DB_USER: root
   - DB_PASS: (empty or your password)
   - DB_NAME: dayflow_hrms

---

### Step 7: Test All New Features

#### Test 1: OTP Email Verification
1. Go to: http://localhost/dayflow/auth/register.php
2. Fill in registration form:
   - Employee ID: TEST001
   - Name: Test User
   - Email: your-email@example.com
   - Password: (follow requirements)
   - Role: Employee
3. Click **Register**
4. ✅ You should see: "Registration successful! An OTP has been sent..."
5. Check your email for OTP (or see OTP in success message)
6. Click **"Verify Email with OTP"** button
7. Enter the 6-digit OTP code
8. Click **Verify OTP**
9. ✅ Should see: "Email verified successfully!"

#### Test 2: Salary Updates
1. Login as **Admin**:
   - Email: admin@dayflow.com
   - Password: admin123
2. Go to **Payroll Management**
3. Click **Update** on any employee
4. Change Basic Salary (e.g., 50000)
5. Click **Update Salary**
6. ✅ Should see updated salary immediately
7. Logout and login as that employee
8. Go to **My Payroll**
9. ✅ Should see updated salary

#### Test 3: Employee Attendance Details
1. Login as **Admin**
2. Go to **Attendance Management**
3. Find any employee in the table
4. **Click on the employee's name** (it's a link)
5. ✅ Should see Weekly attendance (last 7 days)
6. Click **Monthly** tab
7. ✅ Should see Monthly attendance with total days present
8. Click **Back to All** to return

#### Test 4: IST Timezone
1. Login as **Employee**
2. Go to **My Attendance**
3. Click **Check In**
4. ✅ Time shown should be in IST (Indian Standard Time)
5. Note the time (e.g., 14:30 IST)
6. Click **Check Out** later
7. ✅ Check-out time also in IST

---

## 🔧 Troubleshooting

### Problem: Database Update Failed
**Error:** "Duplicate column name 'otp_code'"
- **Solution:** Columns already exist, skip database update step

**Error:** "Table 'users' doesn't exist"
- **Solution:** Database not created, run `database/schema.sql` first

**Error:** "Access denied"
- **Solution:** Check MySQL credentials in `config/db.php`

### Problem: OTP Email Not Sending
**Solution:**
- Check if email is in spam folder
- OTP is shown in success message if email fails (for demo)
- For production, configure SMTP in `config/email.php`

### Problem: Features Not Working
**Solution:**
1. Clear browser cache (Ctrl + Shift + Delete)
2. Hard refresh page (Ctrl + F5)
3. Check browser console for errors (F12)
4. Verify all files are updated correctly
5. Check Apache error logs

### Problem: Time Not in IST
**Solution:**
1. Verify `config/db.php` has: `date_default_timezone_set('Asia/Kolkata');`
2. Restart Apache
3. Clear browser cache

### Problem: Salary Not Updating
**Solution:**
1. Check database connection
2. Verify user has admin role
3. Check browser console for errors
4. Try updating again

---

## 📁 File Structure Check

Your project should have this structure:

```
dayflow/
├── admin/
│   ├── attendance.php (updated)
│   └── payroll.php (updated)
├── auth/
│   ├── register.php (updated)
│   └── verify_otp.php (new)
├── config/
│   ├── db.php (updated)
│   └── email.php (new)
├── employee/
│   ├── attendance.php (updated)
│   └── payroll.php (updated)
├── assets/
│   └── style.css (updated)
└── database/
    └── schema_update_otp.sql
```

---

## ✅ Verification Checklist

After completing all steps, verify:

- [ ] Database update SQL executed successfully
- [ ] XAMPP Apache is running (green)
- [ ] XAMPP MySQL is running (green)
- [ ] Can access http://localhost/dayflow
- [ ] Can login as admin
- [ ] Can login as employee
- [ ] OTP verification works
- [ ] Salary updates reflect on both pages
- [ ] Attendance details show weekly/monthly
- [ ] Check-in/out times are in IST

---

## 🚀 Quick Start (If Everything is Already Updated)

If you've already done the database update and files are in place:

1. **Start XAMPP** (Apache + MySQL)
2. **Open browser:** http://localhost/dayflow
3. **Login and test features**

That's it! The system is ready to use.

---

## 📞 Need Help?

1. Check `UPDATE_FEATURES_GUIDE.md` for detailed feature documentation
2. Check `QUICK_UPDATE_STEPS.md` for quick reference
3. Verify all files are in correct locations
4. Check browser console (F12) for JavaScript errors
5. Check Apache error logs for PHP errors

---

## 🎯 Summary

**To update and run:**
1. ✅ Run database SQL update
2. ✅ Verify files are updated
3. ✅ Start XAMPP services
4. ✅ Test all features

**That's it!** Your updated HRMS system is ready! 🎉

