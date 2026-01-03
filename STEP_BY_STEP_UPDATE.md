# Step-by-Step Database Update Guide

## For Existing Database - Adding Email Verification

Follow these steps to update your existing database:

---

## Step 1: Open phpMyAdmin

1. Make sure **XAMPP is running** (Apache and MySQL services should be green)
2. Open your web browser
3. Go to: **http://localhost/phpmyadmin**
4. You should see the phpMyAdmin interface

---

## Step 2: Select Your Database

1. Look at the **left sidebar** in phpMyAdmin
2. Click on **`dayflow_hrms`** database
   - If you don't see it, check the database name you used
3. The database should be highlighted/selected

---

## Step 3: Open SQL Tab

1. Look at the **top menu bar** in phpMyAdmin
2. Click on the **"SQL"** tab
   - It's usually next to "Structure", "Search", etc.
3. You'll see a text area where you can enter SQL commands

---

## Step 4: Copy the SQL Code

1. Open the file: `database/schema_update_email_verification.sql`
2. **Copy ALL the contents** of that file (Ctrl+A, then Ctrl+C)
3. The SQL code should look like this:
   ```sql
   USE dayflow_hrms;

   ALTER TABLE users 
   ADD COLUMN IF NOT EXISTS email_verified TINYINT(1) DEFAULT 0 AFTER email,
   ADD COLUMN IF NOT EXISTS verification_token VARCHAR(100) NULL AFTER email_verified;

   CREATE INDEX IF NOT EXISTS idx_verification_token ON users(verification_token);
   ```

---

## Step 5: Paste into phpMyAdmin

1. Go back to phpMyAdmin (SQL tab)
2. **Paste** the SQL code into the text area (Ctrl+V)
3. Make sure the code is in the text box

---

## Step 6: Execute the SQL

1. Look for the **"Go"** button at the bottom right of the SQL text area
2. Click **"Go"** button
3. Wait a moment for the query to execute

---

## Step 7: Check for Success

You should see a **green success message** like:
- "2 rows affected" or
- "Your SQL query has been executed successfully"

**If you see an error:**
- If it says "column already exists" - that's OK, the columns are already there
- If it says something else, note the error message

---

## Step 8: Verify the Changes

1. In the left sidebar, click on **`dayflow_hrms`** database
2. Click on **`users`** table
3. Click on the **"Structure"** tab
4. You should now see these new columns:
   - `email_verified` (type: tinyint)
   - `verification_token` (type: varchar(100))
5. If you see them, the update was successful! ✅

---

## Step 9: Update Existing Admin User

Since you have an existing admin user, you need to mark it as verified:

### Option A: Via phpMyAdmin SQL Tab

1. Go to **SQL** tab again
2. Copy and paste this SQL:
   ```sql
   UPDATE users SET email_verified = 1 WHERE email = 'admin@dayflow.com';
   ```
3. Click **"Go"**
4. You should see "1 row affected"

### Option B: Via Setup Script

1. Open browser
2. Go to: **http://localhost/dayflow/setup/create_admin.php**
3. The script will update the admin user and mark it as verified

---

## Step 10: Verify Admin User

1. Go to **SQL** tab in phpMyAdmin
2. Run this query to check:
   ```sql
   SELECT email, email_verified FROM users WHERE email = 'admin@dayflow.com';
   ```
3. You should see `email_verified` = 1 (or true)

---

## ✅ Done!

Your database is now updated. You can:

1. **Test the login:**
   - Go to: http://localhost/dayflow/auth/login.php
   - Login with admin credentials

2. **Register a new user:**
   - The new registration system with email verification will work
   - Password security rules will be enforced

---

## Troubleshooting

### Error: "Column 'email_verified' already exists"
- **Solution:** This means the columns are already added. You can skip to Step 9.

### Error: "Table 'users' doesn't exist"
- **Solution:** Check if your database name is correct. Make sure you selected the right database in Step 2.

### Error: "Unknown database 'dayflow_hrms'"
- **Solution:** The database might have a different name. Check your database name and update the SQL code accordingly.

### Can't find phpMyAdmin
- **Solution:** Make sure XAMPP MySQL is running. Try: http://localhost/phpmyadmin

### SQL syntax error
- **Solution:** Make sure you copied the ENTIRE SQL code correctly. Check for any missing semicolons or quotes.

---

## Quick Reference

**Files needed:**
- `database/schema_update_email_verification.sql`

**URLs:**
- phpMyAdmin: http://localhost/phpmyadmin
- Setup script: http://localhost/dayflow/setup/create_admin.php
- Login page: http://localhost/dayflow/auth/login.php

**SQL Commands Summary:**
```sql
-- Add columns (Step 4-6)
ALTER TABLE users 
ADD COLUMN email_verified TINYINT(1) DEFAULT 0 AFTER email,
ADD COLUMN verification_token VARCHAR(100) NULL AFTER email_verified;

CREATE INDEX idx_verification_token ON users(verification_token);

-- Mark admin as verified (Step 9)
UPDATE users SET email_verified = 1 WHERE email = 'admin@dayflow.com';
```

---

**That's it! Your database is updated and ready to use the new login features!** 🎉

