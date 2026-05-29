<?php
session_start();
include('dbconnect.php');

// Check if user is logged in
if (!isset($_SESSION['username']) || !isset($_SESSION['role'])) {
    header("Location: login.php");
    exit();
}

// Initialize variables to avoid undefined variable warnings
$current_user = $_SESSION['username'] ?? '';
$user_role = $_SESSION['role'] ?? '';
$full_name = $email = $dob = $mobile_number = $aadhar_number = '';
$success_message = '';
$error_message = '';
$current_password_hash = '';

// Fetch existing user data to pre-fill the form
if (!empty($current_user)) {
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
}

// Handle form submission for updating profile - only for plant supervisors
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
    if ($user_role !== 'Plant Supervisor') {
        $error_message = "Only Plant Supervisors are authorized to update profile data.";
    } else {
        $full_name = $_POST['full_name'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $dob = $_POST['dob'] ?? '';
        $mobile_number = $_POST['mobile_number'] ?? '';
        $aadhar_number = $_POST['aadhar_number'] ?? '';

        // Validate required fields
        if (empty($full_name) || empty($email) || empty($password) || empty($dob) || empty($mobile_number) || empty($aadhar_number)) {
            $error_message = "Please fill all required fields.";
        } else {
           
                $new_password = $password; // Re-hash password

                $update_query = "UPDATE signup_table 
                                SET full_name = ?, 
                                    email = ?, 
                                    password = ?, 
                                    dob = ?,
                                    mobile_number = ?, 
                                    aadhar_number = ?
                                WHERE username = ?";
                $stmt = mysqli_prepare($con, $update_query);
                mysqli_stmt_bind_param($stmt, "sssssss", $full_name, $email, $new_password, $dob, $mobile_number, $aadhar_number, $current_user);
                
                $update_query_login = "UPDATE login_table SET password = ? WHERE username = ?";
                $stmt_login = mysqli_prepare($con, $update_query_login);
                mysqli_stmt_bind_param($stmt_login, "ss", $new_password, $current_user);

                if (mysqli_stmt_execute($stmt) && mysqli_stmt_execute($stmt_login)) {
                    $success_message = "Profile updated successfully!";
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
        
        .data-table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        
        .data-table th {
            background-color: #f5f5f5;
            padding: 12px 15px;
            text-align: left;
            font-weight: bold;
            color: #333;
            border-bottom: 2px solid #ddd;
        }
        
        .data-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #eee;
            color: #444;
        }
        
        .data-table tr:hover {
            background-color: #f9f9f9;
        }
        
        .data-table tr:nth-child(even) {
            background-color: #f8f8f8;
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
        
        .btn-danger {
            background-color: #dc3545;
            color: white;
        }
        
        .btn-success {
            background-color: #28a745;
            color: white;
        }
        
        .btn-warning {
            background-color: #ffc107;
            color: #333;
        }
        
        .status-indicator {
            display: inline-block;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            margin-right: 5px;
        }
        
        .status-normal {
            background-color: #28a745;
        }
        
        .status-warning {
            background-color: #ffc107;
        }
        
        .status-critical {
            background-color: #dc3545;
        }
        
        .status-maintenance {
            background-color: #6c757d;
        }
        
        .equipment-form {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            margin-bottom: 30px;
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
        
        textarea.form-control {
            min-height: 100px;
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

        .details-container {
            margin-top: 20px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background-color: #f9f9f9;
        }

        .details-container p {
            margin: 5px 0;
            color: #444;
        }

        .error-message {
            color: #dc3545;
            margin-bottom: 15px;
            font-weight: bold;
        }
        
        .user-image {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
            margin-left: 10px;
            vertical-align: middle;
        }
        
        .avatar {
            width: 60px;
            height: 60px;
            background-color: #0066cc;
            color: white;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-left: 10px;
            vertical-align: middle;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <div class="company-name">GUJARAT STATE ELECTRICITY CORPORATION LIMITED</div>
            <div class="user-info">Plantsupervisor: <span>
                <?php echo htmlspecialchars($current_user); ?> 
            </span></div>
        </header>
        
        <div class="tab-container">
            <div class="tab-nav">
                <button class="tab-btn" onclick="window.location.href='safetymeasure.php'">Safety Measures</button>
                <button class="tab-btn" onclick="window.location.href='reportissue.php'">Report Issues</button>
                <button class="tab-btn active" onclick="window.location.href='update_profile_plant.php'">Update Profile</button>
                <button class="tab-btn" onclick="window.location.href='logout.php'">Logout</button>
            </div>
            <div class="tab-content active">
                <div class="content">
                    <h2 class="section-title">Update Profile</h2>
                        <div class="form-container">
                            <?php if (!empty($success_message)): ?>
                                <div class="success-message"><?php echo htmlspecialchars($success_message); ?></div>
                            <?php endif; ?>
                            <?php if (!empty($error_message)): ?>
                                <div class="error-message"><?php echo htmlspecialchars($error_message); ?></div>
                            <?php endif; ?>

                            <?php if ($user_role === 'Plant Supervisor'): ?>
                                <form method="POST" action="">
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

                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                                        <div class="error-message" id="email-error"></div>
                                    </div>

                                    <div class="form-group">
                                        <label for="password">Current Password</label>
                                        <input type="password" class="form-control" id="password" name="password" placeholder="Enter your current password to confirm" required>
                                        <div class="description">Enter your current password to update your profile.</div>
                                        <div class="error-message" id="password-error"></div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-col">
                                            <div class="form-group">
                                                <label for="dob">Date of Birth</label>
                                                <input type="date" class="form-control" id="dob" name="dob" value="<?php echo htmlspecialchars($dob); ?>"  required>
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

                                    <div class="form-group">
                                        <label for="aadhar-number">Aadhar Card Number</label>
                                        <input type="text" class="form-control" id="aadhar-number" name="aadhar_number" value="<?php echo htmlspecialchars($aadhar_number); ?>" pattern="[0-9]{12}" maxlength="12" required>
                                        <div class="error-message" id="aadhar-number-error"></div>
                                    </div>

                                    <button type="submit" name="update_profile">Update Profile</button>
                                </form>
                            <?php else: ?>
                                <div class="error-message">Only Plant Supervisors can update profile data. Please contact your administrator if you need to make changes.</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <?php mysqli_close($con); ?>
</body>
</html>