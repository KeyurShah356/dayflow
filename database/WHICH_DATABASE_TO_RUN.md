# Which Database File to Run - Complete Guide

## 📋 Database Files Available

You have these SQL files in your `database/` folder:

1. **`schema.sql`** (4 KB) - Original base schema
2. **`schema_update_email_verification.sql`** (1 KB) - Email verification update
3. **`schema_update_otp.sql`** (1 KB) - OTP verification update
4. **`update_database.sql`** (1 KB) - Combined update script
5. **`COMPLETE_SCHEMA.sql`** (NEW) - Complete updated schema (all-in-one)

---

## 🎯 Choose Based on Your Situation

### Option 1: FRESH Installation (New Database)
**Use:** `COMPLETE_SCHEMA.sql` ⭐ **RECOMMENDED**

**When to use:**
- Setting up the system for the first time
- Starting with a fresh/empty database
- Want everything in one file

**Steps:**
1. Open phpMyAdmin: http://localhost/phpmyadmin
2. Delete existing `dayflow_hrms` database (if exists)
3. Click **Import** tab
4. Choose file: `database/COMPLETE_SCHEMA.sql`
5. Click **Go**
6. ✅ Complete database created with all features!

**What it includes:**
- ✅ All base tables (users, employee_profiles, attendance, leaves, payroll)
- ✅ Email verification fields
- ✅ OTP verification fields
- ✅ All indexes and relationships
- ✅ Everything in one file!

---

### Option 2: UPDATE Existing Database
**Use:** `update_database.sql` ⭐ **RECOMMENDED FOR UPDATES**

**When to use:**
- Database already exists
- Already have data in the database
- Just need to add OTP features

**Steps:**
1. Open phpMyAdmin: http://localhost/phpmyadmin
2. Select `dayflow_hrms` database
3. Click **SQL** tab
4. Click **Choose File** or paste SQL from `update_database.sql`
5. Click **Go**
6. ✅ OTP columns added!

**What it does:**
- Adds `otp_code` column
- Adds `otp_expires_at` column
- Creates index for faster lookups
- Safe to run multiple times

---

### Option 3: Step-by-Step Updates (If Already Have Base Schema)
**Use:** Run updates in order

**When to use:**
- Have base schema but missing email verification
- Want to apply updates one by one

**Steps:**
1. Run `schema.sql` (if not already run)
2. Run `schema_update_email_verification.sql`
3. Run `schema_update_otp.sql` (or `update_database.sql`)

---

## 🚀 Quick Decision Guide

### I'm starting fresh / New installation
→ Use **`COMPLETE_SCHEMA.sql`**
- One file, everything included
- No need to run multiple updates
- Clean and simple

### I already have the database with data
→ Use **`update_database.sql`**
- Adds only the new OTP fields
- Preserves all existing data
- Safe and quick

### I'm not sure what I have
→ Check your database first:
1. Open phpMyAdmin
2. Select `dayflow_hrms` database
3. Click on `users` table
4. Check if `otp_code` column exists:
   - ✅ **Exists:** You're already updated, no need to run anything
   - ❌ **Doesn't exist:** Run `update_database.sql`

---

## 📊 Comparison Table

| File | Size | Use Case | Includes OTP? |
|------|------|----------|---------------|
| `schema.sql` | 4 KB | Base schema only | ❌ No |
| `schema_update_email_verification.sql` | 1 KB | Add email verification | ❌ No |
| `schema_update_otp.sql` | 1 KB | Add OTP only | ✅ Yes |
| `update_database.sql` | 1 KB | Add OTP (with checks) | ✅ Yes |
| `COMPLETE_SCHEMA.sql` | ~4 KB | Everything in one | ✅ Yes |

---

## ✅ Recommended Approach

### For Most Users:
**Use `COMPLETE_SCHEMA.sql` for fresh installs**
**Use `update_database.sql` for existing databases**

---

## 🔧 How to Run in phpMyAdmin

### Method 1: Import File
1. Open phpMyAdmin
2. Select database (or create new)
3. Click **Import** tab
4. Click **Choose File**
5. Select SQL file
6. Click **Go**

### Method 2: SQL Tab
1. Open phpMyAdmin
2. Select database
3. Click **SQL** tab
4. Copy SQL content from file
5. Paste in SQL box
6. Click **Go**

---

## ⚠️ Important Notes

### Before Running:
- ✅ Backup your database if you have existing data
- ✅ Stop the application if it's running
- ✅ Check which columns already exist

### After Running:
- ✅ Verify columns were added (check `users` table)
- ✅ Run `setup/create_admin.php` to create admin user
- ✅ Test the application

---

## 🎯 Summary

**For Fresh Installation:**
→ Run **`COMPLETE_SCHEMA.sql`** ⭐

**For Existing Database:**
→ Run **`update_database.sql`** ⭐

**That's it!** Choose based on whether you're starting fresh or updating existing.

