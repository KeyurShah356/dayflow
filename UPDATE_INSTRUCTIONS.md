# Update Instructions - Enhanced Login System

## New Features Added

1. **Unified Login Page** with Admin/Employee tabs
2. **Login with Employee ID or Email**
3. **Password Security Rules** with validation
4. **Email Verification** system
5. **Enhanced Error Messages**

## Database Update Required

If you already have the database set up, you need to run this SQL to add email verification fields:

**Option 1: Run the update SQL file**
- Open phpMyAdmin
- Select `dayflow_hrms` database
- Go to SQL tab
- Copy and paste the contents of `database/schema_update_email_verification.sql`
- Click Go

**Option 2: Manual SQL**
```sql
USE dayflow_hrms;

ALTER TABLE users 
ADD COLUMN email_verified TINYINT(1) DEFAULT 0 AFTER email,
ADD COLUMN verification_token VARCHAR(100) NULL AFTER email_verified;

CREATE INDEX idx_verification_token ON users(verification_token);
```

## For New Installations

If you're setting up fresh, the updated `schema.sql` already includes the email verification fields. Just import it as usual.

## Update Existing Admin User

After running the database update, run the setup script again to mark admin as verified:
- Go to: http://localhost/dayflow/setup/create_admin.php

Or manually update in phpMyAdmin:
```sql
UPDATE users SET email_verified = 1 WHERE email = 'admin@dayflow.com';
```

## New Files Created

- `auth/verify_email.php` - Email verification handler
- `database/schema_update_email_verification.sql` - Database update script

## Updated Files

- `auth/login.php` - New unified login with tabs
- `auth/register.php` - Password security rules and email verification
- `database/schema.sql` - Added email verification fields
- `setup/create_admin.php` - Marks admin as verified

## Password Security Rules

Passwords must contain:
- At least 8 characters
- At least one uppercase letter (A-Z)
- At least one lowercase letter (a-z)
- At least one number (0-9)
- At least one special character (!@#$%^&*)

## Email Verification Flow

1. User registers → Receives verification link (shown on screen for demo)
2. User clicks verification link → Email is verified
3. User can now login

**Note:** In a production environment, the verification link would be sent via email. For demo purposes, it's displayed on the registration success page.

## Testing

1. **Test Employee Login:**
   - Register a new employee account
   - Verify email using the link
   - Login with Employee ID or Email
   - Should redirect to employee dashboard

2. **Test Admin Login:**
   - Switch to Admin tab
   - Login with admin credentials (admin@dayflow.com or ADMIN001)
   - Should redirect to admin dashboard

3. **Test Password Validation:**
   - Try registering with weak password
   - Should show error messages
   - Try with strong password - should work

4. **Test Email Verification:**
   - Register without verifying
   - Try to login - should show "Please verify your email" error
   - Verify email via link
   - Login should work

## Notes

- Admin user created via setup script is automatically verified
- All new registrations require email verification
- Login works with either Employee ID or Email address
- Role-specific login tabs prevent wrong role login attempts

