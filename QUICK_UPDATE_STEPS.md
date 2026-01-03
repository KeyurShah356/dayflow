# Quick Update Steps

## 🚀 Steps to Run the Updated Code

### Step 1: Database Update (Required)
1. Open phpMyAdmin: http://localhost/phpmyadmin
2. Select `dayflow_hrms` database
3. Click **SQL** tab
4. Copy and paste this SQL:
```sql
ALTER TABLE users 
ADD COLUMN otp_code VARCHAR(6) NULL AFTER verification_token,
ADD COLUMN otp_expires_at TIMESTAMP NULL AFTER otp_code;

CREATE INDEX idx_otp_code ON users(otp_code);
```
5. Click **Go**

### Step 2: Start XAMPP
1. Open XAMPP Control Panel
2. Start **Apache**
3. Start **MySQL**

### Step 3: Access Application
- Open browser: http://localhost/dayflow

---

## ✅ What's New

### 1. Email Verification with OTP
- **Before:** Demo verification link shown on page
- **Now:** OTP sent to registered email address
- **How it works:**
  1. Register new user
  2. OTP sent to email automatically
  3. Click "Verify Email with OTP" button
  4. Enter 6-digit OTP code
  5. Email verified!

### 2. Salary Updates
- Admin updates salary → Instantly reflects on:
  - ✅ Admin Payroll page
  - ✅ Employee Payroll page
- **How to test:**
  1. Login as admin
  2. Go to Payroll → Update salary
  3. Click "Update Salary"
  4. See updated value immediately
  5. Login as employee → Check "My Payroll" → See updated salary

### 3. Employee Attendance Details
- **Click on employee name** in admin attendance table
- View **Weekly** attendance (last 7 days)
- View **Monthly** attendance (current month)
- See total days present count

### 4. Indian Standard Time (IST)
- All check-in/check-out times now use **IST**
- Times are accurate according to Indian Standard Time
- Applied to all attendance operations

---

## 🧪 Quick Tests

### Test OTP Verification:
1. Go to: http://localhost/dayflow/auth/register.php
2. Register a new user
3. Check email for OTP (or see OTP in success message)
4. Click "Verify Email with OTP"
5. Enter OTP code
6. ✅ Email verified!

### Test Salary Update:
1. Login as admin
2. Payroll → Click "Update" on any employee
3. Change salary → Click "Update Salary"
4. ✅ See updated salary immediately
5. Login as that employee → My Payroll
6. ✅ See updated salary

### Test Attendance Details:
1. Login as admin
2. Attendance Management
3. **Click on any employee name**
4. ✅ See Weekly attendance
5. Click "Monthly" tab
6. ✅ See Monthly attendance with total days

### Test IST Time:
1. Login as employee
2. Attendance → Click "Check In"
3. ✅ Time shown is in IST (Indian Standard Time)

---

## 📝 Important Notes

### Email Configuration
- System uses PHP `mail()` function
- For production, configure SMTP settings in `config/email.php`
- For local testing, OTP is shown in success message if email fails

### Database
- Must run the SQL update (Step 1) for OTP to work
- Existing users won't be affected
- New registrations will use OTP verification

---

## 🔧 Troubleshooting

**OTP not received?**
- Check spam folder
- OTP shown in success message if email fails
- Verify email address is correct

**Salary not updating?**
- Clear browser cache
- Refresh page
- Check database connection

**Attendance details not showing?**
- Click on employee name (not just view)
- Ensure employee has attendance records
- Check admin role permissions

**Time not in IST?**
- Verify database update completed
- Clear browser cache
- Check `config/db.php` has timezone setting

---

## 📁 New Files Created

1. `config/email.php` - Email sending
2. `auth/verify_otp.php` - OTP verification page
3. `UPDATE_FEATURES_GUIDE.md` - Detailed guide
4. `QUICK_UPDATE_STEPS.md` - This file

---

**All features are ready!** Follow Step 1 (Database Update) first, then test all features.

