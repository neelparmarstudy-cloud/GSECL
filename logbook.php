<?php
// Start the session
session_start();

// Check if user is logged in and is an Admin
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'Admin') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GSECL Daily Work Logbook - Admin</title>
    
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
            margin: 0 auto;
            background: linear-gradient(135deg, rgba(255,255,255,0.9) 0%, rgba(240,240,240,0.9) 100%);
            padding: 0 1rem;
        }

        .header-inner {
            width: 100%;
            max-width: 1280px;
            margin: 0 auto;
        }
        
        header {
            background-color: #fff;
            padding: 15px 20px;
            border-bottom: 1px solid #ddd;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .logo {
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }
        
        .user-info {
            font-size: 14px;
            color: #666;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .user-info span {
            color: #0066cc;
            font-weight: bold;
        }
        
        .role {
            font-size: 12px;
            color: #999;
        }
        
        .tab-container {
            margin-bottom: 30px;
        }
        
        .tab-nav {
            display: flex;
            border-bottom: 1px solid #ddd;
            margin-bottom: 20px;
            gap: 0;
            flex-wrap: wrap;
        }
        
        .tab-btn {
            padding: 10px 20px;
            background: none;
            border: none;
            border-bottom: 3px solid transparent;
            cursor: pointer;
            font-weight: bold;
            color: #666;
            white-space: nowrap;
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

        .content-wrapper {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            min-height: 600px;
        }
        
        .user-image {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }
        
        .avatar {
            width: 40px;
            height: 40px;
            background-color: #0066cc;
            color: white;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }

        iframe {
            width: 100%;
            border: none;
            min-height: 600px;
        }
    </style>
</head>
<body>
    <header>
        <div class="header-inner">
            <div style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
                <div class="logo">GUJARAT STATE ELECTRICITY CORPORATION LIMITED</div>
                <div class="user-info">
                    <span class="role">System Administrator</span>
                    <?php
                        include('dbconnect.php');
                        
                        // Get username from session
                        $username = $_SESSION['username'];
                        
                        // Fetch admin's full name
                        $query = "SELECT full_name FROM signup_table WHERE username = ? AND role = 'Admin' AND approved = 'approved'";
                        $stmt = mysqli_prepare($con, $query);
                        mysqli_stmt_bind_param($stmt, "s", $username);
                        mysqli_stmt_execute($stmt);
                        $result = mysqli_stmt_get_result($stmt);
                        
                        $full_name = "Admin";
                        if ($result && mysqli_num_rows($result) > 0) {
                            $admin = mysqli_fetch_assoc($result);
                            $full_name = $admin['full_name'];
                        }
                        mysqli_stmt_close($stmt);
                        
                        // Fetch admin's photo
                        $photo_query = "SELECT photo FROM user_photo WHERE username = ?";
                        $photo_stmt = mysqli_prepare($con, $photo_query);
                        mysqli_stmt_bind_param($photo_stmt, "s", $username);
                        mysqli_stmt_execute($photo_stmt);
                        $photo_result = mysqli_stmt_get_result($photo_stmt);
                        
                        $photo_path = '';
                        if ($photo_result && mysqli_num_rows($photo_result) > 0) {
                            $photo_row = mysqli_fetch_assoc($photo_result);
                            $photo_filename = $photo_row['photo'];
                            $photo_path = 'signup/' . $photo_filename;
                        } else {
                            $photo_path = 'signup/default.jpg';
                        }
                        mysqli_stmt_close($photo_stmt);
                        
                        echo "<span><b>(" . htmlspecialchars($full_name) . ")</b></span>";
                    ?>
                    <?php if (!empty($photo_path) && file_exists($photo_path)): ?>
                        <img src="<?php echo htmlspecialchars($photo_path); ?>" alt="Admin Photo" class="user-image">
                    <?php else: ?>
                        <div class="avatar"><?php echo strtoupper(substr($full_name, 0, 1)); ?></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>

    <div class="container">
        <div class="tab-container">
            <div class="tab-nav">
                <button class="tab-btn active" onclick="window.location.href='manage_signup_requests.php'">Manage Sign-up Requests</button>
                <button class="tab-btn" onclick="window.location.href='delete_users.php'">Block Users</button>
                <button class="tab-btn" onclick="window.location.href='overtime_records.php'">Overtime Records</button>
                <button class="tab-btn" onclick="window.location.href='view_users.php'">View Users</button>
                <button class="tab-btn" onclick="window.location.href='equipment_management.php'">Add/Delete Equip</button>
                <button class="tab-btn" onclick="window.location.href='update_profile_admin.php'">Update Profile</button>
                <button class="tab-btn" onclick="window.location.href='logout.php'">Logout</button>
            </div>
            <div class="tab-content active">
                <div class="content-wrapper">
                    <iframe name="contentFrame" src="manage_signup_requests.php"></iframe>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
<?php
// Close database connection
mysqli_close($con);
?>