# Fresh Database Setup - Step by Step

Since you're creating the database again from scratch, follow these steps:

## Step 1: Create Database

1. Open phpMyAdmin: http://localhost/phpmyadmin
2. Click "New" in left sidebar
3. Database name: `dayflow_hrms`
4. Collation: `utf8mb4_general_ci` (or leave default)
5. Click "Create"

## Step 2: Import Main Schema

1. In phpMyAdmin, select `dayflow_hrms` database (left sidebar)
2. Click "Import" tab
3. Click "Choose File"
4. Select: `database/schema.sql`
5. Click "Go"
6. Wait for success message

## Step 3: Add OTP Fields (Required)

The main schema.sql has email verification fields, but OTP fields need to be added:

1. In phpMyAdmin, select `dayflow_hrms` database
2. Click "SQL" tab
3. Copy and paste this SQL:

```sql
USE dayflow_hrms;

ALTER TABLE users 
ADD COLUMN otp_code VARCHAR(6) NULL AFTER verification_token,
ADD COLUMN otp_expires_at TIMESTAMP NULL AFTER otp_code;

CREATE INDEX idx_otp_code ON users(otp_code);
```

4. Click "Go"
5. Should see success message

**OR** use the update file:
- Click "Import" tab
- Choose file: `database/schema_update_otp.sql`
- Click "Go"

## Step 4: Create Admin User

1. Open browser
2. Go to: http://localhost/dayflow/setup/create_admin.php
3. Should see: "Admin user created successfully!"

## Step 5: Done! ✅

Your database is ready. You can now:
- Login at: http://localhost/dayflow/auth/login.php
- Register new users

---

## Summary

**Files to run:**
1. ✅ `database/schema.sql` - Main database structure (REQUIRED)
2. ✅ `database/schema_update_otp.sql` - Add OTP fields (REQUIRED for OTP verification)

**Files you DON'T need (for fresh install):**
- ❌ `database/schema_update_email_verification.sql` - Only for existing databases

---

## Quick Checklist

- [ ] Database `dayflow_hrms` created
- [ ] `schema.sql` imported successfully
- [ ] OTP fields added (schema_update_otp.sql)
- [ ] Admin user created (setup/create_admin.php)
- [ ] Can access login page

---

## If You Get Errors

**"Table already exists" error:**
- Drop the database and recreate it, OR
- Use the update files instead of main schema

**"Column already exists" error:**
- That's OK, just continue

**Any other error:**
- Check that MySQL is running in XAMPP
- Verify database name is `dayflow_hrms`
- Make sure you selected the database before running SQL

