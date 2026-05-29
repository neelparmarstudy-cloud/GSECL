<?php
session_start();
include('dbconnect.php');

// Check if user is logged in
if (!isset($_SESSION['username']) || !isset($_SESSION['role'])) {
    header("Location: login.php");
    exit();
}

// Initialize variables
$current_user = $_SESSION['username'];
$user_role = $_SESSION['role'];
$full_name = $email = $dob = $mobile_number = $aadhar_number = $photo_path = '';
$success_message = '';
$error_message = '';
$current_password_hash = '';

// Fetch logged-in user's data
$query = "SELECT full_name, email, password, dob, mobile_number, aadhar_number 
          FROM signup_table 
          WHERE username = ?";
$stmt = mysqli_prepare($con, $query);
mysqli_stmt_bind_param($stmt, "s", $current_user);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);
    $full_name = $user['full_name'] ?? '';
    $email = $user['email'] ?? '';
    $current_password_hash = $user['password'] ?? '';
    $dob = $user['dob'] ?? '';
    $mobile_number = $user['mobile_number'] ?? '';
    $aadhar_number = $user['aadhar_number'] ?? '';
} else {
    $error_message = "User not found.";
}
mysqli_stmt_close($stmt);

// Fetch current photo
$photo_query = "SELECT photo FROM user_photo WHERE username = ?";
$stmt = mysqli_prepare($con, $photo_query);
mysqli_stmt_bind_param($stmt, "s", $current_user);
mysqli_stmt_execute($stmt);
$photo_result = mysqli_stmt_get_result($stmt);
if (mysqli_num_rows($photo_result) > 0) {
    $photo_row = mysqli_fetch_assoc($photo_result);
    $photo_path = 'signup/' . $photo_row['photo'];
}
mysqli_stmt_close($stmt);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
    if ($user_role !== 'Admin') {
        $error_message = "Only Admins are authorized to update their profile data.";
    } else {
        $full_name = $_POST['full_name'] ?? '';
        $email = $_POST['email'] ?? '';
        $new_password = $_POST['new_password'] ?? '';
        $dob = $_POST['dob'] ?? '';
        $mobile_number = $_POST['mobile_number'] ?? '';
        $aadhar_number = $_POST['aadhar_number'] ?? '';

        // Validate required fields
        if (empty($full_name) || empty($email) || empty($dob) || empty($mobile_number) || empty($aadhar_number)) {
            $error_message = "Please fill all required fields.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error_message = "Invalid email format.";
        } elseif (!preg_match('/^[0-9]{10}$/', $mobile_number)) {
            $error_message = "Mobile number must be 10 digits.";
        } elseif (!preg_match('/^[0-9]{12}$/', $aadhar_number)) {
            $error_message = "Aadhar number must be 12 digits.";
        } else {
            // Handle photo upload
            if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] == UPLOAD_ERR_OK) {
                $file_tmp = $_FILES['profile_photo']['tmp_name'];
                $file_name = $_FILES['profile_photo']['name'];
                $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
                $allowed_ext = ['jpg', 'jpeg', 'png'];

                if (!in_array($file_ext, $allowed_ext)) {
                    $error_message = "Invalid file format. Only JPG, JPEG, and PNG are allowed.";
                } elseif ($_FILES['profile_photo']['size'] > 5 * 1024 * 1024) {
                    $error_message = "File size exceeds 5MB limit.";
                } else {
                    $new_file_name = $current_user . '_' . time() . '.' . $file_ext;
                    $upload_path = 'signup/' . $new_file_name;

                    if (move_uploaded_file($file_tmp, $upload_path)) {
                        // Update or insert photo in user_photo table
                        $check_photo_query = "SELECT photo FROM user_photo WHERE username = ?";
                        $stmt = mysqli_prepare($con, $check_photo_query);
                        mysqli_stmt_bind_param($stmt, "s", $current_user);
                        mysqli_stmt_execute($stmt);
                        $check_result = mysqli_stmt_get_result($stmt);

                        if (mysqli_num_rows($check_result) > 0) {
                            // Delete old photo file if it exists
                            $old_photo = mysqli_fetch_assoc($check_result)['photo'];
                            if ($old_photo && file_exists('signup/' . $old_photo)) {
                                unlink('signup/' . $old_photo);
                            }
                            // Update photo
                            $update_photo_query = "UPDATE user_photo SET photo = ? WHERE username = ?";
                            $stmt = mysqli_prepare($con, $update_photo_query);
                            mysqli_stmt_bind_param($stmt, "ss", $new_file_name, $current_user);
                        } else {
                            // Insert new photo
                            $insert_photo_query = "INSERT INTO user_photo (username, photo) VALUES (?, ?)";
                            $stmt = mysqli_prepare($con, $insert_photo_query);
                            mysqli_stmt_bind_param($stmt, "ss", $current_user, $new_file_name);
                        }

                        if (mysqli_stmt_execute($stmt)) {
                            $photo_path = $upload_path; // Update displayed photo
                        } else {
                            $error_message = "Error updating photo: " . mysqli_error($con);
                        }
                        mysqli_stmt_close($stmt);
                    } else {
                        $error_message = "Failed to upload photo.";
                    }
                }
            }

            // Proceed with profile update if no errors
            if (empty($error_message)) {
                // Hash new password if provided
                $update_password = !empty($new_password) ? password_hash($new_password, PASSWORD_DEFAULT) : $current_password_hash;

                $update_query = "UPDATE signup_table 
                                SET full_name = ?, email = ?, password = ?, dob = ?, mobile_number = ?, aadhar_number = ?
                                WHERE username = ?";
                $stmt = mysqli_prepare($con, $update_query);
                mysqli_stmt_bind_param($stmt, "sssssss", $full_name, $email, $update_password, $dob, $mobile_number, $aadhar_number, $current_user);

                // Update login_table if password changed
                $stmt_login = null;
                if (!empty($new_password)) {
                    $update_query_login = "UPDATE login_table SET password = ? WHERE username = ?";
                    $stmt_login = mysqli_prepare($con, $update_query_login);
                    mysqli_stmt_bind_param($stmt_login, "ss", $update_password, $current_user);
                }

                if (mysqli_stmt_execute($stmt) && (!$stmt_login || mysqli_stmt_execute($stmt_login))) {
                    $success_message = "Profile updated successfully!";
                } else {
                    $error_message = "Error updating profile: " . mysqli_error($con);
                }
                mysqli_stmt_close($stmt);
                if ($stmt_login) {
                    mysqli_stmt_close($stmt_login);
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GSECL Daily Work Logbook - Admin Update Profile</title>
    <link rel="stylesheet" href="style2.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }
        body {
            background-color: #f5f5f5;
            color: #111827;
        }
        .container {
            width: 100%;
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 1rem;
        }
        .header-inner {
            width: 100%;
            max-width: 1280px;
            margin: 0 auto;
        }
        header {
            background-color: #fff;
            padding: 1rem 0;
            border-bottom: 1px solid #e5e7eb;
        }
        .logo {
            font-size: 1.15rem;
            font-weight: 700;
            color: #111827;
        }
        .user-role {
            font-size: 0.85rem;
            color: #6b7280;
        }
        .user-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: #475569;
        }
        .user-info span {
            font-weight: 700;
            color: #2563eb;
        }
        .user-image,
        .avatar {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            object-fit: cover;
            background: #2563eb;
            color: white;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
        }
        .tab-container {
            margin: 1.5rem 0 2rem;
        }
        .tab-nav {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            border-bottom: 1px solid #e5e7eb;
        }
        .tab-btn {
            padding: 0.9rem 1.2rem;
            border: none;
            background: white;
            border-bottom: 3px solid transparent;
            color: #475569;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .tab-btn:hover {
            color: #1d4ed8;
        }
        .tab-btn.active {
            color: #1d4ed8;
            border-bottom-color: #1d4ed8;
        }
        .content-wrapper {
            background: white;
            padding: 1.5rem;
            border-radius: 0.75rem;
            box-shadow: 0 1px 3px rgba(15, 23, 42, 0.08);
        }
        .section-title {
            font-size: 1.25rem;
            margin-bottom: 1rem;
            color: #111827;
        }
        .form-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 0;
            max-width: 800px;
            margin: 0 auto;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
            font-size: 14px;
            text-transform: uppercase;
        }
        .form-control {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #d1d5db;
            border-radius: 4px;
            font-size: 14px;
            background-color: #f9fafb;
            color: #374151;
        }
        .form-control:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        input[readonly] {
            background-color: #e5e7eb;
            cursor: not-allowed;
        }
        .form-row {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        .form-col {
            flex: 1;
            min-width: 220px;
        }
        .description {
            font-size: 12px;
            color: #6b7280;
            margin-top: 5px;
        }
        button {
            background-color: #2563eb;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            font-size: 14px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #1d4ed8;
        }
        .success-message {
            color: #16a34a;
            margin-bottom: 15px;
            font-weight: bold;
            font-size: 14px;
        }
        .error-message {
            color: #dc2626;
            margin-bottom: 15px;
            font-weight: bold;
            font-size: 14px;
        }
        .photo-preview {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 10px;
            border: 2px solid #d1d5db;
        }
        .avatar {
            width: 100px;
            height: 100px;
            background-color: #0066cc;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        @media (max-width: 900px) {
            .tab-nav { justify-content: center; }
            header .header-inner > div { display: flex; flex-direction: column; gap: 0.75rem; }
            .content-wrapper { padding: 1rem; }
            .form-row { flex-direction: column; }
        }
    </style>
</head>
<body>
    <header>
        <div class="header-inner">
            <div style="display: flex; justify-content: space-between; align-items: center; width: 100%; flex-wrap: wrap; gap: 0.75rem;">
                <div>
                    <div class="logo">GUJARAT STATE ELECTRICITY CORPORATION LIMITED</div>
                    <div class="user-role">System Administrator</div>
                </div>
                <div class="user-info">
                    <span>(<?php echo htmlspecialchars($full_name); ?>)</span>
                    <?php if (!empty($photo_path) && file_exists($photo_path)): ?>
                        <img src="<?php echo htmlspecialchars($photo_path); ?>" alt="Admin Photo" class="user-image">
                    <?php else: ?>
                        <div class="avatar"><?php echo strtoupper(substr($full_name ?: $current_user, 0, 1)); ?></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>
    <div class="container">
        <div class="tab-container">
            <div class="tab-nav">
                <button class="tab-btn" onclick="window.location.href='manage_signup_requests.php'">Manage Sign-up Requests</button>
                <button class="tab-btn" onclick="window.location.href='delete_users.php'">Block Users</button>
                <button class="tab-btn" onclick="window.location.href='overtime_records.php'">Overtime Records</button>
                <button class="tab-btn" onclick="window.location.href='view_users.php'">View Users</button>
                <button class="tab-btn" onclick="window.location.href='equipment_management.php'">Add/Delete Equip</button>
                <button class="tab-btn active" onclick="window.location.href='update_profile_admin.php'">Update Profile</button>
                <button class="tab-btn" onclick="window.location.href='logout.php'">Logout</button>
            </div>
            <div class="tab-content active">
                <div class="content-wrapper">
                    <h1 class="section-title">Admin Update Profile</h1>
                    <div class="form-container">
                    <?php if (!empty($success_message)): ?>
                        <div class="success-message"><?php echo htmlspecialchars($success_message); ?></div>
                    <?php endif; ?>
                    <?php if (!empty($error_message)): ?>
                        <div class="error-message"><?php echo htmlspecialchars($error_message); ?></div>
                    <?php endif; ?>

                    <?php if ($user_role === 'Admin'): ?>
                        <form method="POST" action="" enctype="multipart/form-data">
                            <!-- Profile Photo -->
                            <div class="form-group">
                                <label for="profile-photo">Profile Photo</label>
                                <?php if (!empty($photo_path) && file_exists($photo_path)): ?>
                                    <img src="<?php echo htmlspecialchars($photo_path); ?>" alt="Profile Photo" class="photo-preview">
                                <?php else: ?>
                                    <div class="avatar"><?php echo strtoupper(substr($full_name ?: $current_user, 0, 1)); ?></div>
                                <?php endif; ?>
                                <input type="file" class="form-control" id="profile-photo" name="profile_photo" accept=".jpg,.jpeg,.png">
                                <div class="description">Upload a new photo (JPG, JPEG, PNG, max 5MB).</div>
                            </div>

                            <div class="form-row">
                                <div class="form-col">
                                    <div class="form-group">
                                        <label for="full-name">Full Name</label>
                                        <input type="text" class="form-control" id="full-name" name="full_name" value="<?php echo htmlspecialchars($full_name); ?>" required>
                                    </div>
                                </div>
                                <div class="form-col">
                                    <div class="form-group">
                                        <label for="username">Username</label>
                                        <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($current_user); ?>" readonly required>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="new-password">New Password</label>
                                <input type="password" class="form-control" id="new-password" name="new_password" placeholder="Enter new password (leave blank to keep unchanged)">
                                <div class="description">Leave blank to keep the current password.</div>
                            </div>

                            <div class="form-row">
                                <div class="form-col">
                                    <div class="form-group">
                                        <label for="dob">Date of Birth</label>
                                        <input type="date" class="form-control" id="dob" name="dob" value="<?php echo htmlspecialchars($dob); ?>" max="<?php echo date('Y-m-d'); ?>" required>
                                    </div>
                                </div>
                                <div class="form-col">
                                    <div class="form-group">
                                        <label for="mobile-number">Mobile Number</label>
                                        <input type="tel" class="form-control" id="mobile-number" name="mobile_number" value="<?php echo htmlspecialchars($mobile_number); ?>" pattern="[6-9]{1}[0-9]{9}" maxlength="10"required>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="aadhar-number">Aadhar Card Number</label>
                                <input type="text" class="form-control" id="aadhar-number" name="aadhar_number" value="<?php echo htmlspecialchars($aadhar_number); ?>" pattern="[0-9]{12}" maxlength="12"required>
                            </div>

                            <button type="submit" name="update_profile">Update Profile</button>
                        </form>
                    <?php else: ?>
                        <div class="error-message">Only Admins can update their profile data. Please contact your administrator if you need to make changes.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <?php mysqli_close($con); ?>
</body>
</html>