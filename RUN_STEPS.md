# Steps to Run the Updated Code

## 📋 Prerequisites Checklist
- [ ] XAMPP is installed
- [ ] Project is located at: `C:\xampp\htdocs\dayflow\`
- [ ] Database `dayflow_hrms` exists and is set up

---

## 🚀 Step-by-Step Instructions

### Step 1: Start XAMPP Services
1. Open **XAMPP Control Panel**
2. Click **Start** button next to **Apache**
   - Wait until it shows green/running status
3. Click **Start** button next to **MySQL**
   - Wait until it shows green/running status

✅ **Check:** Both Apache and MySQL should show green status

---

### Step 2: Verify Uploads Directory
The `uploads/profile_pictures/` directory should already exist. If not, create it:

**Option A: Using File Explorer**
1. Navigate to: `C:\xampp\htdocs\dayflow\`
2. Create folder named: `uploads`
3. Inside `uploads`, create folder named: `profile_pictures`

**Option B: Using Command Prompt**
```cmd
cd C:\xampp\htdocs\dayflow
mkdir uploads\profile_pictures
```

✅ **Check:** Directory exists at: `C:\xampp\htdocs\dayflow\uploads\profile_pictures\`

---

### Step 3: Access the Application
1. Open your web browser (Chrome, Firefox, Edge, etc.)
2. Go to: **http://localhost/dayflow**
   - Or directly: **http://localhost/dayflow/auth/login.php**

✅ **Check:** Login page should load without errors

---

### Step 4: Login to Test Features

#### Login as Employee:
1. Enter your employee credentials
2. Click **Login**
3. You should be redirected to Employee Dashboard

#### Login as Admin (to set hire dates):
- **Email:** `admin@dayflow.com`
- **Password:** `admin123`
- Click **Login**
- You should be redirected to Admin Dashboard

---

### Step 5: Test Employee Profile Features

#### Navigate to Profile:
1. Click **Profile** in the top navigation menu
2. You should see:
   - **Profile Photo** section at the top
   - **Personal Information** section with all fields
   - **Job Information** section

#### Test Profile Photo Upload:
1. In the **Profile Photo** section
2. Click **Choose File** button
3. Select an image file (JPEG, PNG, or GIF - max 5MB)
4. Click **Upload Photo** button
5. ✅ Photo should appear immediately

#### Test Phone Number Edit:
1. In **Personal Information** section, find **Phone Number**
2. Click **Edit** button next to phone number
3. Enter a new phone number
4. Click **Save** button
5. ✅ You should see "✓ Saved" message
6. ✅ Changes save instantly (no page refresh)

#### Test Address Edit:
1. In **Personal Information** section, find **Address**
2. Click **Edit** button next to address
3. Enter a new address
4. Click **Save** button
5. ✅ You should see "✓ Saved" message
6. ✅ Changes save instantly

#### View Hire Date:
- In **Personal Information** section
- **Hire Date** shows the date set by admin
- If not set, shows "Not set by admin"

---

### Step 6: Test Admin Features

#### Set Hire Date for Employee:
1. Login as **Admin**
2. Click **Employee Management** in navigation
3. Find an employee in the table
4. Click **Edit** button for that employee
5. Scroll down to **Hire Date** field
6. Select a date from the date picker
7. Click **Update Employee** button
8. ✅ Hire date is now set

#### View Employee List with Updates:
1. In **Employee Management** page
2. You should see:
   - Profile photos in the first column
   - Employee ID, Name, Email, Role
   - Phone numbers (updated by employees)
   - Addresses (updated by employees)
   - Hire dates (set by admin)
   - Action buttons (Edit, Payroll)

---

## ✅ Verification Checklist

After following all steps, verify:

- [ ] XAMPP Apache and MySQL are running
- [ ] Can access http://localhost/dayflow
- [ ] Can login as employee
- [ ] Can login as admin
- [ ] Profile page shows all personal information
- [ ] Can upload profile photo
- [ ] Can edit phone number with instant save
- [ ] Can edit address with instant save
- [ ] Admin can set hire date
- [ ] Hire date appears on employee profile
- [ ] Profile photos appear in admin employee list
- [ ] Updated phone/address appear in admin dashboard

---

## 🔧 Troubleshooting

### Problem: Page shows 404 Error
**Solution:**
- Verify project is at: `C:\xampp\htdocs\dayflow\`
- Check Apache is running in XAMPP
- Try: http://localhost/dayflow/auth/login.php

### Problem: Database Connection Error
**Solution:**
- Check MySQL is running in XAMPP
- Verify database `dayflow_hrms` exists
- Check `config/db.php` has correct credentials

### Problem: Profile Photo Not Uploading
**Solution:**
- Verify `uploads/profile_pictures/` directory exists
- Check file size is under 5MB
- Ensure file is JPEG, PNG, or GIF format
- Check directory permissions (should be writable)

### Problem: Instant Updates Not Working
**Solution:**
- Open browser Developer Tools (Press F12)
- Go to **Console** tab
- Look for JavaScript errors
- Verify `api/update_profile.php` file exists
- Ensure you're logged in

### Problem: Changes Not Showing
**Solution:**
- Refresh the page (Press F5)
- Clear browser cache (Ctrl + Shift + Delete)
- Check database directly in phpMyAdmin

---

## 📁 Important File Locations

```
C:\xampp\htdocs\dayflow\
├── employee/
│   └── profile.php (updated)
├── admin/
│   └── employees.php (updated)
├── api/
│   └── update_profile.php (new file)
├── uploads/
│   └── profile_pictures/ (new directory)
└── assets/
    └── style.css (updated)
```

---

## 🎯 Quick Test Summary

1. **Start XAMPP** → Apache + MySQL
2. **Open Browser** → http://localhost/dayflow
3. **Login as Employee** → Go to Profile
4. **Upload Photo** → Choose file → Upload
5. **Edit Phone** → Click Edit → Enter number → Save
6. **Edit Address** → Click Edit → Enter address → Save
7. **Login as Admin** → Employee Management
8. **Set Hire Date** → Edit employee → Set date → Update
9. **View List** → See photos, phone, address, hire date

---

## 📞 Need More Help?

- Check `QUICK_START_PROFILE_UPDATES.md` for detailed guide
- Check `PROFILE_UPDATE_INSTRUCTIONS.md` for technical details
- Check browser console (F12) for errors
- Verify all files are in correct locations

---

**That's it!** Your updated HRMS system is ready to use with all new profile features! 🎉

