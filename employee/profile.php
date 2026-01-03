<?php


require_once '../includes/auth_check.php';
require_once '../config/db.php';

$page_title = 'My Profile';

$success = '';
$error = '';


$stmt = $conn->prepare("SELECT u.*, ep.phone, ep.address, ep.position, ep.department, ep.hire_date, ep.profile_picture 
                        FROM users u 
                        LEFT JOIN employee_profiles ep ON u.id = ep.user_id 
                        WHERE u.id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$profile = $stmt->get_result()->fetch_assoc();
$stmt->close();


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['profile_picture'])) {
    $upload_dir = '../uploads/profile_pictures/';
    
  
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    
    $file = $_FILES['profile_picture'];
    $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
    $max_size = 5 * 1024 * 1024; 
    
    if ($file['error'] === UPLOAD_ERR_OK) {
       
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        
        if (!in_array($mime_type, $allowed_types)) {
            $error = 'Invalid file type. Please upload a JPEG, PNG, or GIF image.';
        } elseif ($file['size'] > $max_size) {
            $error = 'File size exceeds 5MB limit.';
        } else {
           
            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = 'profile_' . $user_id . '_' . time() . '.' . $extension;
            $filepath = $upload_dir . $filename;
            
           
            if (!empty($profile['profile_picture']) && file_exists('../' . $profile['profile_picture'])) {
                unlink('../' . $profile['profile_picture']);
            }
            
          
            if (move_uploaded_file($file['tmp_name'], $filepath)) {
                $relative_path = 'uploads/profile_pictures/' . $filename;
               
                $stmt = $conn->prepare("UPDATE employee_profiles SET profile_picture = ? WHERE user_id = ?");
                $stmt->bind_param("si", $relative_path, $user_id);
                
                if ($stmt->execute()) {
                    $success = 'Profile picture updated successfully';
                    $profile['profile_picture'] = $relative_path;
                } else {
                    $error = 'Failed to update profile picture in database';
                    unlink($filepath); 
                }
                $stmt->close();
            } else {
                $error = 'Failed to upload file.';
            }
        }
    } else {
        $error = 'File upload error.';
    }
}

include '../includes/header.php';
?>

<h2>My Profile</h2>

<?php if ($success): ?>
    <div class="alert alert-success" id="success-message"><?php echo htmlspecialchars($success); ?></div>
<?php endif; ?>

<?php if ($error): ?>
    <div class="alert alert-error" id="error-message"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<div class="profile-container">
   
    <div class="profile-section">
        <h3>Profile Photo</h3>
        <div class="profile-photo-container">
            <div class="profile-photo-wrapper">
                <?php if (!empty($profile['profile_picture'])): ?>
                    <img src="../<?php echo htmlspecialchars($profile['profile_picture']); ?>" 
                         alt="Profile Photo" 
                         class="profile-photo" 
                         id="profile-photo-img">
                <?php else: ?>
                    <div class="profile-photo-placeholder" id="profile-photo-img">
                        <span>No Photo</span>
                    </div>
                <?php endif; ?>
            </div>
            <form method="POST" action="" enctype="multipart/form-data" id="photo-upload-form">
                <div class="form-group">
                    <label for="profile_picture">Change Profile Photo</label>
                    <input type="file" id="profile_picture" name="profile_picture" accept="image/jpeg,image/jpg,image/png,image/gif" required>
                    <small>Max size: 5MB. Allowed formats: JPEG, PNG, GIF</small>
                </div>
                <button type="submit" class="btn btn-primary">Upload Photo</button>
            </form>
        </div>
    </div>
    
   
    <div class="profile-section">
        <h3>Personal Information</h3>
        <table class="info-table">
            <tr>
                <th>Employee ID:</th>
                <td><?php echo htmlspecialchars($profile['employee_id']); ?></td>
            </tr>
            <tr>
                <th>Name:</th>
                <td><?php echo htmlspecialchars($profile['name']); ?></td>
            </tr>
            <tr>
                <th>Email:</th>
                <td><?php echo htmlspecialchars($profile['email']); ?></td>
            </tr>
            <tr>
                <th>Role:</th>
                <td><?php echo ucfirst($profile['role']); ?></td>
            </tr>
            <tr>
                <th>Phone Number:</th>
                <td>
                    <span id="phone-display"><?php echo htmlspecialchars($profile['phone'] ?? 'Not set'); ?></span>
                    <input type="text" id="phone-input" 
                           value="<?php echo htmlspecialchars($profile['phone'] ?? ''); ?>" 
                           style="display: none; width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;">
                    <button type="button" id="edit-phone-btn" class="btn btn-sm btn-secondary" style="margin-left: 0.5rem;">Edit</button>
                    <button type="button" id="save-phone-btn" class="btn btn-sm btn-primary" style="display: none; margin-left: 0.5rem;">Save</button>
                    <button type="button" id="cancel-phone-btn" class="btn btn-sm btn-secondary" style="display: none; margin-left: 0.5rem;">Cancel</button>
                    <span id="phone-status" style="margin-left: 0.5rem;"></span>
                </td>
            </tr>
            <tr>
                <th>Address:</th>
                <td>
                    <span id="address-display"><?php echo htmlspecialchars($profile['address'] ?? 'Not set'); ?></span>
                    <textarea id="address-input" 
                              style="display: none; width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px; min-height: 80px;"><?php echo htmlspecialchars($profile['address'] ?? ''); ?></textarea>
                    <div style="margin-top: 0.5rem;">
                        <button type="button" id="edit-address-btn" class="btn btn-sm btn-secondary">Edit</button>
                        <button type="button" id="save-address-btn" class="btn btn-sm btn-primary" style="display: none; margin-left: 0.5rem;">Save</button>
                        <button type="button" id="cancel-address-btn" class="btn btn-sm btn-secondary" style="display: none; margin-left: 0.5rem;">Cancel</button>
                        <span id="address-status" style="margin-left: 0.5rem;"></span>
                    </div>
                </td>
            </tr>
            <tr>
                <th>Hire Date:</th>
                <td><?php echo $profile['hire_date'] ? date('M d, Y', strtotime($profile['hire_date'])) : 'Not set by admin'; ?></td>
            </tr>
        </table>
    </div>
    
 
    <div class="profile-section">
        <h3>Job Information</h3>
        <table class="info-table">
            <tr>
                <th>Position:</th>
                <td><?php echo htmlspecialchars($profile['position'] ?? 'Not set'); ?></td>
            </tr>
            <tr>
                <th>Department:</th>
                <td><?php echo htmlspecialchars($profile['department'] ?? 'Not set'); ?></td>
            </tr>
        </table>
    </div>
</div>

<script>

let originalPhone = '<?php echo htmlspecialchars($profile['phone'] ?? ''); ?>';

document.getElementById('edit-phone-btn').addEventListener('click', function() {
    document.getElementById('phone-display').style.display = 'none';
    document.getElementById('phone-input').style.display = 'inline-block';
    document.getElementById('edit-phone-btn').style.display = 'none';
    document.getElementById('save-phone-btn').style.display = 'inline-block';
    document.getElementById('cancel-phone-btn').style.display = 'inline-block';
    document.getElementById('phone-input').focus();
});

document.getElementById('cancel-phone-btn').addEventListener('click', function() {
    document.getElementById('phone-input').value = originalPhone;
    document.getElementById('phone-display').style.display = 'inline';
    document.getElementById('phone-input').style.display = 'none';
    document.getElementById('edit-phone-btn').style.display = 'inline-block';
    document.getElementById('save-phone-btn').style.display = 'none';
    document.getElementById('cancel-phone-btn').style.display = 'none';
    document.getElementById('phone-status').textContent = '';
});

document.getElementById('save-phone-btn').addEventListener('click', function() {
    const phone = document.getElementById('phone-input').value.trim();
    const statusEl = document.getElementById('phone-status');
    const saveBtn = document.getElementById('save-phone-btn');
    
    saveBtn.disabled = true;
    statusEl.textContent = 'Saving...';
    statusEl.style.color = '#3498db';
    
    fetch('/dayflow/api/update_profile.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'field=phone&value=' + encodeURIComponent(phone)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            originalPhone = phone;
            document.getElementById('phone-display').textContent = phone || 'Not set';
            document.getElementById('phone-display').style.display = 'inline';
            document.getElementById('phone-input').style.display = 'none';
            document.getElementById('edit-phone-btn').style.display = 'inline-block';
            document.getElementById('save-phone-btn').style.display = 'none';
            document.getElementById('cancel-phone-btn').style.display = 'none';
            statusEl.textContent = '✓ Saved';
            statusEl.style.color = '#27ae60';
            setTimeout(() => {
                statusEl.textContent = '';
            }, 2000);
        } else {
            statusEl.textContent = '✗ Error: ' + (data.message || 'Failed to save');
            statusEl.style.color = '#e74c3c';
        }
        saveBtn.disabled = false;
    })
    .catch(error => {
        statusEl.textContent = '✗ Error: Network error';
        statusEl.style.color = '#e74c3c';
        saveBtn.disabled = false;
    });
});


let originalAddress = '<?php echo htmlspecialchars($profile['address'] ?? ''); ?>';

document.getElementById('edit-address-btn').addEventListener('click', function() {
    document.getElementById('address-display').style.display = 'none';
    document.getElementById('address-input').style.display = 'block';
    document.getElementById('edit-address-btn').style.display = 'none';
    document.getElementById('save-address-btn').style.display = 'inline-block';
    document.getElementById('cancel-address-btn').style.display = 'inline-block';
    document.getElementById('address-input').focus();
});

document.getElementById('cancel-address-btn').addEventListener('click', function() {
    document.getElementById('address-input').value = originalAddress;
    document.getElementById('address-display').style.display = 'inline';
    document.getElementById('address-input').style.display = 'none';
    document.getElementById('edit-address-btn').style.display = 'inline-block';
    document.getElementById('save-address-btn').style.display = 'none';
    document.getElementById('cancel-address-btn').style.display = 'none';
    document.getElementById('address-status').textContent = '';
});

document.getElementById('save-address-btn').addEventListener('click', function() {
    const address = document.getElementById('address-input').value.trim();
    const statusEl = document.getElementById('address-status');
    const saveBtn = document.getElementById('save-address-btn');
    
    saveBtn.disabled = true;
    statusEl.textContent = 'Saving...';
    statusEl.style.color = '#3498db';
    
    fetch('/dayflow/api/update_profile.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'field=address&value=' + encodeURIComponent(address)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            originalAddress = address;
            document.getElementById('address-display').textContent = address || 'Not set';
            document.getElementById('address-display').style.display = 'inline';
            document.getElementById('address-input').style.display = 'none';
            document.getElementById('edit-address-btn').style.display = 'inline-block';
            document.getElementById('save-address-btn').style.display = 'none';
            document.getElementById('cancel-address-btn').style.display = 'none';
            statusEl.textContent = '✓ Saved';
            statusEl.style.color = '#27ae60';
            setTimeout(() => {
                statusEl.textContent = '';
            }, 2000);
        } else {
            statusEl.textContent = '✗ Error: ' + (data.message || 'Failed to save');
            statusEl.style.color = '#e74c3c';
        }
        saveBtn.disabled = false;
    })
    .catch(error => {
        statusEl.textContent = '✗ Error: Network error';
        statusEl.style.color = '#e74c3c';
        saveBtn.disabled = false;
    });
});


setTimeout(function() {
    const successMsg = document.getElementById('success-message');
    const errorMsg = document.getElementById('error-message');
    if (successMsg) successMsg.style.display = 'none';
    if (errorMsg) errorMsg.style.display = 'none';
}, 5000);
</script>

<?php include '../includes/footer.php'; ?>
