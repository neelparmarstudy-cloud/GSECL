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

// Handle form submission to add equipment
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_equipment'])) {
    $equipment_name = $_POST['equipment_name'];
    $equipment_type = $_POST['equipment_type'];
    $serial_number = $_POST['serial_number'];
    $quantity = $_POST['quantity'];

    // Simple validation
    if (empty($equipment_name) || empty($equipment_type) || empty($serial_number) || empty($quantity)) {
        echo "<script>alert('Please fill all fields.');</script>";
    } elseif ($quantity <= 0) {
        echo "<script>alert('Quantity must be a positive number.');</script>";
    } else {
        // Insert the equipment into the database
        $query = "INSERT INTO equipment (equipment_name, equipment_type, serial_number, quantity) 
                  VALUES ('$equipment_name', '$equipment_type', '$serial_number', $quantity)";
        if (mysqli_query($con, $query)) {
            echo "<script>alert('Equipment added successfully!'); window.location.href='equipment_management.php';</script>";
        } else {
            echo "<script>alert('Error adding equipment: " . mysqli_error($con) . "');</script>";
        }
    }
}

// Handle delete action
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    
    // Validate the ID
    if (!is_numeric($id) || $id <= 0) {
        echo "<script>alert('Invalid equipment ID.');</script>";
    } else {
        // Delete the equipment from the database
        $delete_query = "DELETE FROM equipment WHERE id = $id";
        if (mysqli_query($con, $delete_query)) {
            if (mysqli_affected_rows($con) > 0) {
                echo "<script>alert('Equipment deleted successfully!'); window.location.href='equipment_management.php';</script>";
            } else {
                echo "<script>alert('No equipment found with ID $id.');</script>";
            }
        } else {
            echo "<script>alert('Error deleting equipment: " . mysqli_error($con) . "');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Equipment</title>
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
            max-width: 520px;
        }
        .form-box label {
            display: block;
            margin-top: 1rem;
            font-weight: 600;
            color: #334155;
        }
        .form-box input {
            width: 100%;
            padding: 0.85rem 1rem;
            border: 1px solid #d1d5db;
            border-radius: 0.75rem;
            margin-top: 0.4rem;
            background: white;
        }
        .form-box button {
            margin-top: 1.25rem;
            padding: 0.85rem 1.25rem;
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 0.75rem;
            cursor: pointer;
            font-weight: 700;
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
        .delete-btn {
            padding: 0.75rem 1rem;
            background: #ef4444;
            color: white;
            border: none;
            border-radius: 0.75rem;
            cursor: pointer;
            font-weight: 600;
        }
        @media (max-width: 900px) {
            .tab-nav { justify-content: center; }
            header .header-inner > div { display: flex; flex-direction: column; gap: 0.75rem; }
            .content-wrapper { padding: 1rem; }
            .form-box { max-width: 100%; }
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
                <button class="tab-btn" onclick="window.location.href='overtime_records.php'">Overtime Records</button>
                <button class="tab-btn" onclick="window.location.href='view_users.php'">View Users</button>
                <button class="tab-btn active" onclick="window.location.href='equipment_management.php'">Add/Delete Equip</button>
                <button class="tab-btn" onclick="window.location.href='update_profile_admin.php'">Update Profile</button>
                <button class="tab-btn" onclick="window.location.href='logout.php'">Logout</button>
            </div>
            <div class="tab-content active">
                <div class="content-wrapper">
                    <h1 class="section-title">Add / Delete Equipment</h1>
                    <div class="form-box">
                        <form method="POST" action="equipment_management.php">
                            <label>Equipment Name:</label>
                            <input type="text" name="equipment_name" required>

                            <label>Equipment Type:</label>
                            <input type="text" name="equipment_type" required>

                            <label>Serial Number:</label>
                            <input type="text" name="serial_number" required>

                            <label>Quantity:</label>
                            <input type="number" name="quantity" required>

                            <button type="submit" name="add_equipment">Add Equipment</button>
                        </form>
                    </div>
                    <div class="table-container">
                        <table>
                            <tr>
                                <th>Equipment Name</th>
                                <th>Type</th>
                                <th>Serial Number</th>
                                <th>Quantity</th>
                                <th>Action</th>
                            </tr>
                            <?php
                            // Fetch all equipment from the equipment table
                            $query = "SELECT * FROM equipment";
                            $result = mysqli_query($con, $query);

                            if (mysqli_num_rows($result) > 0) {
                                while ($equipment = mysqli_fetch_assoc($result)) {
                                    echo "<tr>";
                                    echo "<td>" . htmlspecialchars($equipment['equipment_name']) . "</td>";
                                    echo "<td>" . htmlspecialchars($equipment['equipment_type']) . "</td>";
                                    echo "<td>" . htmlspecialchars($equipment['serial_number']) . "</td>";
                                    echo "<td>" . htmlspecialchars($equipment['quantity']) . "</td>";
                                    echo "<td>";
                                    echo "<button class='delete-btn' onclick=\"if(confirm('Are you sure you want to delete " . htmlspecialchars($equipment['equipment_name']) . "?')) { window.location.href='equipment_management.php?delete=" . $equipment['id'] . "'; }\">Delete</button>";
                                    echo "</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='5'>No equipment found.</td></tr>";
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