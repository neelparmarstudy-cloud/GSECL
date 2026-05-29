<?php
session_start();
include('dbconnect.php');

$full_name = 'Admin';
$photo_path = 'signup/default.jpg';
$username = $_SESSION['username'] ?? '';
if (!empty($username)) {
    $profile_query = "SELECT full_name FROM signup_table WHERE username = ? AND role = 'Admin' AND approved = 'approved'";
    $stmt = mysqli_prepare($con, $profile_query);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $profile_result = mysqli_stmt_get_result($stmt);
    if ($profile_result && mysqli_num_rows($profile_result) > 0) {
        $profile_row = mysqli_fetch_assoc($profile_result);
        $full_name = $profile_row['full_name'];
    }
    mysqli_stmt_close($stmt);

    $photo_query = "SELECT photo FROM user_photo WHERE username = ?";
    $stmt = mysqli_prepare($con, $photo_query);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $photo_result = mysqli_stmt_get_result($stmt);
    if ($photo_result && mysqli_num_rows($photo_result) > 0) {
        $photo_row = mysqli_fetch_assoc($photo_result);
        $photo_path = 'signup/' . $photo_row['photo'];
    }
    mysqli_stmt_close($stmt);
}

// Handle block action
if (isset($_GET['block'])) {
    $username = $_GET['block'];
    
    // Update signup_table: Change approved_status to 'pending'
    $update_query = "UPDATE signup_table SET approved = 'pending' WHERE username = '$username'";
    
    // Delete user from login_table to prevent login
    $delete_query = "DELETE FROM login_table WHERE username = '$username'";
    
    if (mysqli_query($con, $update_query) && mysqli_query($con, $delete_query)) {
        echo "<script>alert('User blocked successfully!'); window.location.href='delete_users.php';</script>";
    } else {
        echo "<script>alert('Error blocking user.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Block Users</title>
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
        .table-container {
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            min-width: 700px;
        }
        th, td {
            padding: 0.95rem 1rem;
            border: 1px solid #e5e7eb;
            text-align: left;
            color: #1f2937;
        }
        th {
            background: #f8fafc;
            font-weight: 700;
        }
        tr:nth-child(even) {
            background: #f8fafc;
        }
        .button,
        .block-btn,
        .delete-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.75rem 1rem;
            border: none;
            border-radius: 0.65rem;
            font-weight: 600;
            cursor: pointer;
        }
        .button-primary,
        .block-btn {
            background: #2563eb;
            color: white;
        }
        .button-danger,
        .delete-btn {
            background: #ef4444;
            color: white;
        }
        .blocked-text {
            color: #ef4444;
            font-weight: 700;
        }
        @media (max-width: 900px) {
            .tab-nav { justify-content: center; }
            header .header-inner > div { display: flex; flex-direction: column; gap: 0.75rem; }
            .content-wrapper { padding: 1rem; }
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
                        <div class="avatar"><?php echo strtoupper(substr($full_name, 0, 1)); ?></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>
    <div class="container">
        <div class="tab-container">
            <div class="tab-nav">
                <button class="tab-btn" onclick="window.location.href='manage_signup_requests.php'">Manage Sign-up Requests</button>
                <button class="tab-btn active" onclick="window.location.href='delete_users.php'">Block Users</button>
                <button class="tab-btn" onclick="window.location.href='overtime_records.php'">Overtime Records</button>
                <button class="tab-btn" onclick="window.location.href='view_users.php'">View Users</button>
                <button class="tab-btn" onclick="window.location.href='equipment_management.php'">Add/Delete Equip</button>
                <button class="tab-btn" onclick="window.location.href='update_profile_admin.php'">Update Profile</button>
                <button class="tab-btn" onclick="window.location.href='logout.php'">Logout</button>
            </div>
            <div class="tab-content active">
                <div class="content-wrapper">
                    <h1 class="section-title">Manage Users</h1>
                    <div class="table-container">
                        <table>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Approval Status</th>
            <th>Actions</th>
        </tr>
        <?php
        // Fetch all users from signup_table
        $query = "SELECT full_name, email, role, username, approved FROM signup_table WHERE approved ='approved'";
        $result = mysqli_query($con, $query);

        if (mysqli_num_rows($result) > 0) {
            while ($user = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($user['full_name']) . "</td>";
                echo "<td>" . htmlspecialchars($user['email']) . "</td>";
                echo "<td>" . htmlspecialchars($user['role']) . "</td>";
                echo "<td>" . htmlspecialchars($user['approved']) . "</td>"; // Show approval status
                echo "<td>";
                
                if ($user['approved'] !== 'pending') {
                    echo "<button class='block-btn' onclick=\"if(confirm('Are you sure you want to block " . htmlspecialchars($user['full_name']) . "?')) { window.location.href='delete_users.php?block=" . htmlspecialchars($user['username']) . "'; }\">Block</button>";
                } else {
                    echo "<span class='blocked-text'>Blocked</span>";
                }

                echo "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='5'>No users found.</td></tr>";
        }
        ?>
                        </table>
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
