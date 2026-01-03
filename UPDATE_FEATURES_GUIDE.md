# Update Features Guide

## New Features Implemented

### 1. ✅ Email Verification with OTP
- **Changed from:** Demo verification link displayed on registration page
- **Changed to:** OTP sent via email to registered email address
- **Features:**
  - OTP is automatically generated and sent to user's email
  - OTP expires in 10 minutes
  - Properly formatted email with HTML styling
  - OTP verification page with clean interface
  - Success message properly formatted in registration box

**Files Modified:**
- `auth/register.php` - Updated to send OTP via email
- `auth/verify_otp.php` - New OTP verification page
- `config/email.php` - New email sending function
- `assets/style.css` - Updated alert styles for better text wrapping

**Database Update Required:**
Run the SQL from `database/schema_update_otp.sql` to add OTP fields:
```sql
ALTER TABLE users 
ADD COLUMN otp_code VARCHAR(6) NULL AFTER verification_token,
ADD COLUMN otp_expires_at TIMESTAMP NULL AFTER otp_code;

CREATE INDEX idx_otp_code ON users(otp_code);
```

---

### 2. ✅ Salary Updates Reflection
- **Feature:** When admin updates salary, it reflects instantly on both:
  - Admin Payroll page
  - Employee Payroll page
- **Implementation:**
  - Salary update redirects to show updated data
  - Both pages fetch latest salary data from database
  - Changes are visible immediately after update

**Files Modified:**
- `admin/payroll.php` - Added redirect after update to show changes
- `employee/payroll.php` - Already fetches latest data (no changes needed)

---

### 3. ✅ Employee Attendance Details (Weekly & Monthly)
- **Feature:** When clicking on an employee name in admin attendance, view their:
  - Weekly attendance (last 7 days)
  - Monthly attendance (current month)
- **Implementation:**
  - Clickable employee names in attendance table
  - Toggle between Weekly and Monthly views
  - Shows detailed attendance records
  - Displays total days present for monthly view

**Files Modified:**
- `admin/attendance.php` - Added employee detail view with weekly/monthly tabs

---

### 4. ✅ Indian Standard Time (IST) for Check-in/Check-out
- **Feature:** All check-in and check-out times use Indian Standard Time (IST)
- **Implementation:**
  - Timezone set to 'Asia/Kolkata' in database config
  - Applied to all attendance operations
  - All timestamps stored and displayed in IST

**Files Modified:**
- `config/db.php` - Added timezone setting to IST
- `employee/attendance.php` - Ensured IST timezone for check-in/out
- `admin/attendance.php` - Ensured IST timezone for display

---

## How to Apply Updates

### Step 1: Database Update
1. Open phpMyAdmin: http://localhost/phpmyadmin
2. Select `dayflow_hrms` database
3. Go to SQL tab
4. Run this SQL:
```sql
ALTER TABLE users 
ADD COLUMN otp_code VARCHAR(6) NULL AFTER verification_token,
ADD COLUMN otp_expires_at TIMESTAMP NULL AFTER otp_code;

CREATE INDEX idx_otp_code ON users(otp_code);
```

### Step 2: Verify Files
Ensure these new/modified files exist:
- ✅ `config/email.php` (new)
- ✅ `auth/verify_otp.php` (new)
- ✅ `auth/register.php` (modified)
- ✅ `admin/attendance.php` (modified)
- ✅ `admin/payroll.php` (modified)
- ✅ `config/db.php` (modified)

### Step 3: Test Features

**Test OTP Email Verification:**
1. Go to registration page
2. Register a new user
3. Check email for OTP (or see OTP in success message if email sending fails)
4. Click "Verify Email with OTP" button
5. Enter OTP code
6. Verify email successfully

**Test Salary Updates:**
1. Login as admin
2. Go to Payroll Management
3. Click "Update" on any employee
4. Update salary and click "Update Salary"
5. Verify salary shows updated value
6. Login as that employee
7. Go to My Payroll
8. Verify updated salary is visible

**Test Attendance Details:**
1. Login as admin
2. Go to Attendance Management
3. Click on any employee name in the table
4. View Weekly attendance (last 7 days)
5. Click "Monthly" tab to view monthly attendance
6. See total days present count

**Test IST Time:**
1. Login as employee
2. Go to Attendance
3. Click "Check In"
4. Verify time shown is in IST (Indian Standard Time)
5. Check-out time should also be in IST

---

## Email Configuration

The system uses PHP's `mail()` function to send emails. For production use, consider:

1. **Using PHPMailer** (recommended):
   - Install via Composer: `composer require phpmailer/phpmailer`
   - Update `config/email.php` to use PHPMailer
   - Configure SMTP settings

2. **Using SMTP directly:**
   - Update `config/email.php` with your SMTP credentials
   - Configure mail server settings

3. **For local testing:**
   - The current implementation will attempt to send emails
   - If email sending fails, OTP is shown in success message (for demo)
   - Remove OTP display in production

---

## Troubleshooting

### OTP Email Not Sending
- Check PHP mail configuration
- Verify email server settings
- Check spam folder
- For local testing, OTP is shown in success message

### Salary Not Updating
- Verify database connection
- Check user permissions
- Ensure payroll table exists
- Clear browser cache

### Attendance Details Not Showing
- Verify employee ID is correct
- Check database for attendance records
- Ensure user has admin role

### Time Not in IST
- Verify `date_default_timezone_set('Asia/Kolkata')` is in `config/db.php`
- Check server timezone settings
- Clear browser cache

---

## Files Summary

### New Files:
1. `config/email.php` - Email sending functionality
2. `auth/verify_otp.php` - OTP verification page

### Modified Files:
1. `auth/register.php` - OTP generation and email sending
2. `admin/attendance.php` - Employee detail view with weekly/monthly
3. `admin/payroll.php` - Salary update redirect
4. `config/db.php` - IST timezone setting
5. `employee/attendance.php` - IST timezone for check-in/out
6. `employee/payroll.php` - IST timezone
7. `assets/style.css` - Alert text wrapping

---

## Next Steps

1. ✅ Run database update SQL
2. ✅ Test OTP email verification
3. ✅ Test salary updates
4. ✅ Test attendance details view
5. ✅ Verify IST timezone

All features are now implemented and ready for testing!

