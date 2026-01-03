# Profile Update Instructions

## New Features Added

### Employee Profile Enhancements

1. **Personal Information Section**
   - Employee ID (display only)
   - Name (display only)
   - Email (display only)
   - Role (display only)
   - Phone Number (editable with instant updates)
   - Address (editable with instant updates)
   - Hire Date (set by admin, displayed on employee profile)

2. **Profile Photo**
   - Display profile photo on employee profile
   - Upload/change profile photo functionality
   - Photo appears in admin dashboard employee list

3. **Instant Updates**
   - Phone and address fields update instantly via AJAX
   - Changes reflect immediately on employee profile
   - Changes also appear on admin dashboard without page refresh

### Admin Dashboard Enhancements

1. **Employee Management**
   - Set hire date for employees
   - View employee profile photos in employee list
   - See updated phone and address information
   - All employee information displayed in table

## How to Run the Updated Code

### Step 1: Ensure XAMPP is Running
1. Open XAMPP Control Panel
2. Start **Apache** service
3. Start **MySQL** service

### Step 2: Verify Database
1. Open phpMyAdmin: http://localhost/phpmyadmin
2. Ensure `dayflow_hrms` database exists
3. Verify `employee_profiles` table has these columns:
   - `phone` (VARCHAR)
   - `address` (TEXT)
   - `hire_date` (DATE)
   - `profile_picture` (VARCHAR)

### Step 3: Create Uploads Directory
The `uploads/profile_pictures/` directory should be created automatically when you upload a profile photo. However, you can create it manually:

**Option A: Via File Explorer**
1. Navigate to: `C:\xampp\htdocs\dayflow\`
2. Create folder: `uploads`
3. Inside `uploads`, create folder: `profile_pictures`

**Option B: Via Command Prompt**
```cmd
cd C:\xampp\htdocs\dayflow
mkdir uploads
mkdir uploads\profile_pictures
```

### Step 4: Set Directory Permissions (if needed)
If you encounter upload errors:
1. Right-click on `uploads` folder
2. Properties → Security tab
3. Ensure the web server has write permissions

### Step 5: Access the Application
1. Open browser: http://localhost/dayflow
2. Login as admin or employee

### Step 6: Test Features

**As Employee:**
1. Go to Profile page
2. Upload a profile photo
3. Edit phone number - should save instantly
4. Edit address - should save instantly
5. View hire date (if set by admin)

**As Admin:**
1. Go to Employee Management
2. Click Edit on any employee
3. Set hire date
4. View employee list with profile photos
5. See updated phone and address information

## File Structure

```
dayflow/
├── employee/
│   └── profile.php (updated)
├── admin/
│   └── employees.php (updated)
├── api/
│   └── update_profile.php (new)
├── uploads/
│   ├── .htaccess (new)
│   └── profile_pictures/
│       └── .htaccess (new)
└── assets/
    └── style.css (updated)
```

## Troubleshooting

### Profile Photo Not Uploading
- Check `uploads/profile_pictures/` directory exists
- Verify directory permissions (should be writable)
- Check file size (max 5MB)
- Ensure file is JPEG, PNG, or GIF format

### Instant Updates Not Working
- Check browser console for JavaScript errors
- Verify `api/update_profile.php` file exists
- Ensure user is logged in (session active)
- Check database connection

### Hire Date Not Showing
- Admin must set hire date in employee edit form
- Date format: YYYY-MM-DD
- Check database for `hire_date` value

### Changes Not Reflecting
- Clear browser cache
- Refresh the page (F5)
- Check database directly in phpMyAdmin

## Database Notes

The `employee_profiles` table should have:
- `user_id` (INT, Foreign Key)
- `phone` (VARCHAR(20))
- `address` (TEXT)
- `position` (VARCHAR(100))
- `department` (VARCHAR(100))
- `hire_date` (DATE)
- `profile_picture` (VARCHAR(255))

All fields are optional except `user_id`.

## Security Features

- File upload validation (type and size)
- SQL injection prevention (prepared statements)
- XSS prevention (htmlspecialchars)
- Session-based authentication
- Directory protection via .htaccess

## Browser Compatibility

Tested and works on:
- Chrome (latest)
- Firefox (latest)
- Edge (latest)
- Safari (latest)

## Support

If you encounter any issues:
1. Check browser console for errors
2. Check Apache error logs
3. Verify database connection
4. Ensure all files are in correct locations

