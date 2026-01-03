# Quick Start Guide - Profile Updates

## 🚀 Steps to Run the Updated Code

### Prerequisites
- XAMPP installed and running
- Project located at: `C:\xampp\htdocs\dayflow\`
- Database `dayflow_hrms` already created

### Step 1: Start XAMPP Services
1. Open **XAMPP Control Panel**
2. Click **Start** for **Apache**
3. Click **Start** for **MySQL**
   - Both should show green/running status

### Step 2: Verify Uploads Directory
The `uploads/profile_pictures/` directory has been created automatically. If you need to create it manually:

**Windows Command Prompt:**
```cmd
cd C:\xampp\htdocs\dayflow
mkdir uploads\profile_pictures
```

### Step 3: Access the Application
1. Open your browser
2. Go to: **http://localhost/dayflow**
3. Login with your credentials:
   - **Admin**: admin@dayflow.com / admin123
   - **Employee**: Your employee credentials

### Step 4: Test Employee Profile Features

**Navigate to Profile:**
1. Login as an employee
2. Click **Profile** in the navigation menu
3. You should see:
   - Personal Information section with all fields
   - Profile Photo section
   - Job Information section

**Upload Profile Photo:**
1. In Profile Photo section, click **Choose File**
2. Select an image (JPEG, PNG, or GIF, max 5MB)
3. Click **Upload Photo**
4. Photo should appear immediately

**Edit Phone Number:**
1. In Personal Information section, find Phone Number
2. Click **Edit** button
3. Enter new phone number
4. Click **Save**
5. You should see "✓ Saved" confirmation
6. Changes are saved instantly (no page refresh needed)

**Edit Address:**
1. In Personal Information section, find Address
2. Click **Edit** button
3. Enter new address
4. Click **Save**
5. You should see "✓ Saved" confirmation
6. Changes are saved instantly

**View Hire Date:**
- Hire date appears in Personal Information section
- Shows "Not set by admin" if not set yet
- Only admin can set hire date

### Step 5: Test Admin Features

**Set Hire Date:**
1. Login as admin
2. Go to **Employee Management**
3. Click **Edit** on any employee
4. Find **Hire Date** field
5. Select a date
6. Click **Update Employee**
7. Hire date is now set and visible on employee profile

**View Employee List:**
1. In Employee Management page
2. You should see:
   - Profile photos in the table
   - Employee ID, Name, Email, Role
   - Phone, Address, Hire Date
   - Edit and Payroll action buttons

## ✅ Features Checklist

- [x] Employee ID displayed (read-only)
- [x] Name displayed (read-only)
- [x] Email displayed (read-only)
- [x] Role displayed (read-only)
- [x] Phone number editable with instant updates
- [x] Address editable with instant updates
- [x] Hire date set by admin, displayed on profile
- [x] Profile photo upload functionality
- [x] Profile photo change functionality
- [x] Profile photos visible in admin dashboard
- [x] Instant updates reflect on both employee profile and admin dashboard

## 🔧 Troubleshooting

### Issue: Profile photo not uploading
**Solution:**
- Check `uploads/profile_pictures/` directory exists
- Verify file size is under 5MB
- Ensure file is JPEG, PNG, or GIF format
- Check directory permissions (should be writable)

### Issue: Instant updates not working
**Solution:**
- Open browser Developer Tools (F12)
- Check Console tab for JavaScript errors
- Verify `api/update_profile.php` file exists
- Ensure you're logged in

### Issue: Changes not showing
**Solution:**
- Refresh the page (F5)
- Clear browser cache
- Check database in phpMyAdmin

### Issue: Hire date not saving
**Solution:**
- Ensure you're logged in as admin
- Check date format (YYYY-MM-DD)
- Verify database connection

## 📁 New Files Created

1. `api/update_profile.php` - API endpoint for instant profile updates
2. `uploads/.htaccess` - Security for uploads directory
3. `uploads/profile_pictures/.htaccess` - Security for profile pictures
4. `PROFILE_UPDATE_INSTRUCTIONS.md` - Detailed documentation

## 📝 Modified Files

1. `employee/profile.php` - Enhanced with all features
2. `admin/employees.php` - Added hire date and profile photos
3. `assets/style.css` - Added profile photo styles

## 🎯 What's New

### For Employees:
- Complete personal information display
- Editable phone and address with instant save
- Profile photo upload and management
- View hire date (set by admin)

### For Admins:
- Set employee hire dates
- View employee profile photos in list
- See all employee information including phone and address
- Manage complete employee profiles

## 📞 Need Help?

If you encounter any issues:
1. Check the browser console (F12) for errors
2. Verify XAMPP services are running
3. Check database connection in `config/db.php`
4. Review `PROFILE_UPDATE_INSTRUCTIONS.md` for detailed troubleshooting

---

**Ready to use!** All features are implemented and ready for testing.

