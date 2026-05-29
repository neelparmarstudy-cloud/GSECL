<?php
session_start();
include('dbconnect.php');

// Check if user is logged in
if (!isset($_SESSION['username']) || !isset($_SESSION['role'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

$current_user = $_SESSION['username'];

// Initialize variables for form pre-filling
$full_name = $email = $password = $dob = $mobile_number = $aadhar_number = $photo_path = '';
$success_message = $error_message = '';

// Fetch existing user data to pre-fill the form
$query = "SELECT full_name, email, password, dob, mobile_number, aadhar_number 
          FROM signup_table 
          WHERE username = ?";
$stmt = mysqli_prepare($con, $query);
mysqli_stmt_bind_param($stmt, "s", $current_user);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);
    $full_name = $user['full_name'];
    $email = $user['email'];
    $password = $user['password']; // Plain text password
    $dob = $user['dob'];
    $mobile_number = $user['mobile_number'];
    $aadhar_number = $user['aadhar_number'];
} else {
    $error_message = "User not found.";
}
mysqli_stmt_close($stmt);

// Fetch current photo from user_photo table
$photo_query = "SELECT photo FROM user_photo WHERE username = ?";
$stmt = mysqli_prepare($con, $photo_query);
mysqli_stmt_bind_param($stmt, "s", $current_user);
mysqli_stmt_execute($stmt);
$photo_result = mysqli_stmt_get_result($stmt);
if (mysqli_num_rows($photo_result) > 0) {
    $photo_row = mysqli_fetch_assoc($photo_result);
    $photo_path = 'signup/' . $photo_row['photo'];
} else {
    $photo_path = 'signup/' . $current_user . '.jpg'; // Fallback path
}
mysqli_stmt_close($stmt);

// Handle form submission for updating profile and photo
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
    $full_name = $_POST['full_name'] ?? '';
    $email = $_POST['email'] ?? '';
    $new_password = $_POST['password'] ?? ''; // Get the new password from form
    $dob = $_POST['dob'] ?? '';
    $mobile_number = $_POST['mobile_number'] ?? '';
    $aadhar_number = $_POST['aadhar_number'] ?? '';

    // Validate required fields
    if (empty($full_name) || empty($email) || empty($new_password) || empty($dob) || empty($mobile_number) || empty($aadhar_number)) {
        $error_message = "Please fill all required fields.";
    } else {
        // Handle photo upload
        if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] == UPLOAD_ERR_OK) {
            $file_tmp = $_FILES['profile_photo']['tmp_name'];
            $file_name = $_FILES['profile_photo']['name'];
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            $allowed_ext = ['jpg', 'jpeg', 'png'];

            if (!in_array($file_ext, $allowed_ext)) {
                $error_message = "Invalid file format. Only JPG, JPEG, and PNG are allowed.";
            } elseif ($_FILES['profile_photo']['size'] > 5 * 1024 * 1024) { // 5MB limit
                $error_message = "File size exceeds 5MB limit.";
            } else {
                $new_file_name = $current_user . '_' . time() . '.' . $file_ext;
                $upload_path = 'signup/' . $new_file_name;

                if (move_uploaded_file($file_tmp, $upload_path)) {
                    // Update or insert into user_photo table
                    $check_photo_query = "SELECT * FROM user_photo WHERE username = ?";
                    $stmt = mysqli_prepare($con, $check_photo_query);
                    mysqli_stmt_bind_param($stmt, "s", $current_user);
                    mysqli_stmt_execute($stmt);
                    $check_result = mysqli_stmt_get_result($stmt);

                    if (mysqli_num_rows($check_result) > 0) {
                        $update_photo_query = "UPDATE user_photo SET photo = ? WHERE username = ?";
                        $stmt = mysqli_prepare($con, $update_photo_query);
                        mysqli_stmt_bind_param($stmt, "ss", $new_file_name, $current_user);
                    } else {
                        $insert_photo_query = "INSERT INTO user_photo (username, photo) VALUES (?, ?)";
                        $stmt = mysqli_prepare($con, $insert_photo_query);
                        mysqli_stmt_bind_param($stmt, "ss", $current_user, $new_file_name);
                    }
                    if (!mysqli_stmt_execute($stmt)) {
                        $error_message = "Error updating photo: " . mysqli_error($con);
                    } else {
                        $photo_path = $upload_path; // Update displayed photo
                    }
                    mysqli_stmt_close($stmt);
                } else {
                    $error_message = "Failed to upload photo.";
                }
            }
        }

        // Proceed with profile update if no errors
        if (empty($error_message)) {
            $password_to_save = $new_password; // Store password as plain text

            $update_query = "UPDATE signup_table 
                            SET full_name = ?, 
                                email = ?, 
                                password = ?, 
                                dob = ?,
                                mobile_number = ?, 
                                aadhar_number = ?
                            WHERE username = ?";
            $stmt = mysqli_prepare($con, $update_query);
            mysqli_stmt_bind_param($stmt, "sssssss", $full_name, $email, $password_to_save, $dob, $mobile_number, $aadhar_number, $current_user);

            $update_query_login = "UPDATE login_table SET password = ? WHERE username = ?";
            $stmt_login = mysqli_prepare($con, $update_query_login);
            mysqli_stmt_bind_param($stmt_login, "ss", $password_to_save, $current_user);

            if (mysqli_stmt_execute($stmt) && mysqli_stmt_execute($stmt_login)) {
                $success_message = "Profile updated successfully!";
                $password = $new_password; // Update displayed password
            } else {
                $error_message = "Error updating profile: " . mysqli_error($con);
            }
            mysqli_stmt_close($stmt);
            mysqli_stmt_close($stmt_login);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GSEC Daily Work Logbook - Update Profile</title>
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
        }
        
        .container {
            width: 100%;
            max-width: 1280px;
            min-height: 100vh;
            margin: 0 auto;
            background: linear-gradient(135deg, rgba(255,255,255,0.9) 0%, rgba(240,240,240,0.9) 100%);
            padding: 0 1rem;
        }
        
        header {
            background-color: #fff;
            padding: 15px 20px;
            border-bottom: 1px solid #ddd;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .company-name {
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }
        
        .user-info {
            font-size: 14px;
            color: #666;
        }
        
        .user-info span {
            color: #0066cc;
            font-weight: bold;
        }
        
        .main-content {
            display: flex;
            min-height: calc(100vh - 60px);
        }
        
        .content {
            flex: 1;
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .section-title {
            font-size: 20px;
            color: #333;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #ddd;
        }
        
        .btn {
            padding: 6px 12px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            font-size: 14px;
        }
        
        .btn-primary {
            background-color: #0066cc;
            color: white;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }
        
        .form-control {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        
        .form-row {
            display: flex;
            margin: 0 -10px;
        }
        
        .form-col {
            flex: 1;
            padding: 0 10px;
        }
        
        .tab-container {
            margin-bottom: 30px;
        }
        
        .tab-nav {
            display: flex;
            border-bottom: 1px solid #ddd;
            margin-bottom: 20px;
        }
        
        .tab-btn {
            padding: 10px 20px;
            background: none;
            border: none;
            border-bottom: 3px solid transparent;
            cursor: pointer;
            font-weight: bold;
            color: #666;
        }
        
        .tab-btn.active {
            color: #0066cc;
            border-bottom-color: #0066cc;
        }
        
        .tab-content {
            display: none;
        }
        
        .tab-content.active {
            display: block;
        }

        .form-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        .success-message {
            color: #28a745;
            margin-bottom: 15px;
            font-weight: bold;
        }

        .error-message {
            color: #dc3545;
            margin-bottom: 15px;
            font-weight: bold;
        }

        input[readonly] {
            background-color: #f8f8f8;
            cursor: not-allowed;
        }

        button {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
        }

        select {
            appearance: none;
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"><path fill="%23333" d="M7 10l5 5 5-5z"/></svg>') no-repeat right 10px center;
            background-color: #fff;
            padding-right: 30px;
        }

        .description {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }

        .photo-preview {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 10px;
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
    </style>
</head>
<body>
    <div class="container">
        <header>
            <div class="company-name">GUJARAT STATE ELECTRICITY CORPORATION LIMITED</div>
            <div class="user-info">Shift Engineer: <span>
                <?php
                $name = $_SESSION['username'] ?? 'Guest';
                echo htmlspecialchars($name);
                ?>
            </span></div>
        </header>
        
        <div class="main-content">
            <div class="content">
                <h2 class="section-title">Update Profile</h2>
                
                <div class="tab-container">
                    <div class="tab-nav">
                        <button class="tab-btn" onclick="window.location.href='report-new-issue.php'">Report New Issue</button>
                        <button class="tab-btn" onclick="window.location.href='resolve-issue.php'">Resolve Issues</button>
                        <button class="tab-btn" onclick="window.location.href='recent-issues.php'">Pending Issues</button>
                        <button class="tab-btn" onclick="window.location.href='resolved-issues.php'">Solved Issues</button>
                        <button class="tab-btn" onclick="window.location.href='req_plan.php'">Plant Supervisor Request</button>
                        <button class="tab-btn active">Update Profile</button>
                        <button class="tab-btn" onclick="window.location.href='logout.php'">Logout</button>
                    </div>
                    
                    <div class="tab-content active">
                        <div class="form-container">
                            <!-- Display success or error message -->
                            <?php if ($success_message): ?>
                                <div class="success-message"><?php echo htmlspecialchars($success_message); ?></div>
                            <?php endif; ?>
                            <?php if ($error_message): ?>
                                <div class="error-message"><?php echo htmlspecialchars($error_message); ?></div>
                            <?php endif; ?>

                            <form method="POST" action="" enctype="multipart/form-data">
                                <!-- Profile Photo -->
                                <div class="form-group">
                                    <label for="profile-photo">Profile Photo</label>
                                    <?php if (!empty($photo_path) && file_exists($photo_path)): ?>
                                        <img src="<?php echo htmlspecialchars($photo_path); ?>" alt="Profile Photo" class="photo-preview">
                                    <?php else: ?>
                                        <div class="avatar"><?php echo strtoupper(substr($current_user, 0, 1)); ?></div>
                                    <?php endif; ?>
                                    <input type="file" class="form-control" id="profile-photo" name="profile_photo" accept=".jpg,.jpeg,.png">
                                    <div class="description">Upload a new photo (JPG, JPEG, PNG, max 5MB).</div>
                                </div>

                                <!-- Full Name and Username -->
                                <div class="form-row">
                                    <div class="form-col">
                                        <div class="form-group">
                                            <label for="full-name">Full Name</label>
                                            <input type="text" class="form-control" id="full-name" name="full_name" value="<?php echo htmlspecialchars($full_name); ?>" required>
                                            <div class="error-message" id="full-name-error"></div>
                                        </div>
                                    </div>
                                    <div class="form-col">
                                        <div class="form-group">
                                            <label for="username">Username</label>
                                            <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($current_user); ?>" readonly required>
                                            <div class="error-message" id="username-error"></div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Email -->
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                                    <div class="error-message" id="email-error"></div>
                                </div>

                                <!-- Password (Pre-filled and Editable) -->
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input type="text" class="form-control" id="password" name="password" value="<?php echo htmlspecialchars($password); ?>" required>
                                    <div class="description">Edit your password here.</div>
                                    <div class="error-message" id="password-error"></div>
                                </div>

                                <!-- Date of Birth and Mobile Number -->
                                <div class="form-row">
                                    <div class="form-col">
                                        <div class="form-group">
                                            <label for="dob">Date of Birth</label>
                                            <input type="date" class="form-control" id="dob" name="dob" value="<?php echo htmlspecialchars($dob); ?>" required>
                                            <div class="error-message" id="dob-error"></div>
                                        </div>
                                    </div>
                                    <div class="form-col">
                                        <div class="form-group">
                                            <label for="mobile-number">Mobile Number</label>
                                            <input type="tel" class="form-control" id="mobile-number" name="mobile_number" value="<?php echo htmlspecialchars($mobile_number); ?>" pattern="[6-9]{1}[0-9]{9}" maxlength="10" required>
                                            <div class="error-message" id="mobile-number-error"></div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Aadhar Card Number -->
                                <div class="form-group">
                                    <label for="aadhar-number">Aadhar Card Number</label>
                                    <input type="text" class="form-control" id="aadhar-number" name="aadhar_number" value="<?php echo htmlspecialchars($aadhar_number); ?>" pattern="[0-9]{12}" maxlength="12" required>
                                    <div class="error-message" id="aadhar-number-error"></div>
                                </div>

                                <!-- Submit Button -->
                                <button type="submit" name="update_profile">Update Profile</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php
    // Close the database connection
    mysqli_close($con);
    ?>
</body>
</html>