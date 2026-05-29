<?php
// Start the session and include the database connection
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

// Process form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $date = $_POST['date'];
    $username = $_POST['username'];
    $role = $_POST['role'];
    $hours = $_POST['hours'];
    $reason = $_POST['reason'];

    // Simple validation
    if (empty($date) || empty($username) || empty($role) || empty($hours) || empty($reason)) {
        echo "<script>alert('Please fill all fields.');</script>";
    } elseif ($hours <= 0) {
        echo "<script>alert('Hours must be a positive number.');</script>";
    } else {
        // Insert the record into the overtime table
        $query = "INSERT INTO overtime (date, username, role, hours, reason) 
                  VALUES ('$date', '$username', '$role', $hours, '$reason')";
        if (mysqli_query($con, $query)) {
            echo "<script>alert('Overtime record added!');</script>";
        } else {
            echo "<script>alert('Error adding record.');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Overtime Records</title>
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
        .form-box {
            background: #f8fafc;
            padding: 1.25rem;
            border-radius: 0.75rem;
            margin-bottom: 1.5rem;
            border: 1px solid #e5e7eb;
        }
        .form-group {
            margin-bottom: 1rem;
        }
        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #334155;
        }
        input, select, textarea {
            width: 100%;
            padding: 0.85rem 1rem;
            border: 1px solid #d1d5db;
            border-radius: 0.75rem;
            background: white;
            color: #111827;
        }
        textarea {
            min-height: 140px;
            resize: vertical;
        }
        button,
        .button,
        .block-btn,
        .delete-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.75rem 1rem;
            border: none;
            border-radius: 0.75rem;
            font-weight: 600;
            cursor: pointer;
        }
        button {
            background: #2563eb;
            color: white;
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
        @media (max-width: 900px) {
            .tab-nav { justify-content: center; }
            header .header-inner > div { display: flex; flex-direction: column; gap: 0.75rem; }
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
                <button class="tab-btn" onclick="window.location.href='delete_users.php'">Block Users</button>
                <button class="tab-btn active" onclick="window.location.href='overtime_records.php'">Overtime Records</button>
                <button class="tab-btn" onclick="window.location.href='view_users.php'">View Users</button>
                <button class="tab-btn" onclick="window.location.href='equipment_management.php'">Add/Delete Equip</button>
                <button class="tab-btn" onclick="window.location.href='update_profile_admin.php'">Update Profile</button>
                <button class="tab-btn" onclick="window.location.href='logout.php'">Logout</button>
            </div>
            <div class="tab-content active">
                <div class="content-wrapper">
                    <h1 class="section-title">Add Overtime Record</h1>
                    <div class="form-box">
        <form method="POST" action="">
            <label>Date:</label>
            <input type="date" name="date" required>

            <label>Select User:</label>
            <select name="username" required>
                <option value="">Select User</option>
                <?php
                // Fetch approved users from signup_table
                $user_query = "SELECT username FROM signup_table WHERE approved = 'approved'";
                $user_result = mysqli_query($con, $user_query);
                while ($user = mysqli_fetch_assoc($user_result)) {
                    echo "<option value='" . $user['username'] . "'>" . $user['username'] . "</option>";
                }
                ?>
            </select>

            <label>Select Role:</label>
            <select name="role" required>
                <option value="">Select Role</option>
                <option value="Shift Engineer">Shift Engineer</option>
                <option value="Admin">Admin</option>
                <option value="Plant Supervisor">Plant Supervisor</option>
            </select>

            <label>Hours:</label>
            <input type="number" name="hours" required>

            <label>Reason:</label>
            <textarea name="reason" required></textarea>

            <button type="submit">Submit</button>
        </form>
    </div>

    <h2>Overtime Records</h2>
    <table>
        <tr>
            <th>Date</th>
            <th>User</th>
            <th>Role</th>
            <th>Hours</th>
            <th>Reason</th>
        </tr>
        <?php
        // Fetch and display overtime records
        $records_query = "SELECT * FROM overtime ORDER BY date DESC";
        $records_result = mysqli_query($con, $records_query);
        if (mysqli_num_rows($records_result) > 0) {
            while ($record = mysqli_fetch_assoc($records_result)) {
                echo "<tr>";
                echo "<td>" . $record['date'] . "</td>";
                echo "<td>" . $record['username'] . "</td>";
                echo "<td>" . $record['role'] . "</td>";
                echo "<td>" . $record['hours'] . "</td>";
                echo "<td>" . $record['reason'] . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='5'>No records found.</td></tr>";
        }
        ?>
    </table>
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