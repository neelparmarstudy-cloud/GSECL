<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Safety Measure</title>
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
    <header>
        <div class="header-inner">
            <div class="flex justify-between items-center">
                <h1 class="header-title">GUJARAT STATE ELECTRICITY CORPORATION LIMITED</h1>
             
                <div class="user-info">Plant Supervisor: <span>
                    <?php
                    $name = $_SESSION['username'] ?? 'Guest';
                    echo htmlspecialchars($name);

                    $conn = mysqli_connect('localhost', 'root', '', 'log_book');
                    if (!$conn) {
                        die("<div class='message error'>Connection failed: " . mysqli_connect_error() . "</div>");
                    }

                    $photo_query = "SELECT photo FROM user_photo WHERE username = '$name'";
                    $photo_result = mysqli_query($conn, $photo_query);
                    $photo_path = '';
                    if ($photo_result && mysqli_num_rows($photo_result) > 0) {
                        $photo_row = mysqli_fetch_assoc($photo_result);
                        $photo_path = 'signup/' . $photo_row['photo'];
                    } else {
                        $photo_path = 'signup/' . $name . '.jpg';
                    }
                    ?>
                </span>
                <?php if (!empty($photo_path) && file_exists($photo_path)): ?>
                    <img src="<?php echo htmlspecialchars($photo_path); ?>" alt="User Photo" class="user-image">
                <?php else: ?>
                    <div class="avatar"><?php echo strtoupper(substr($name, 0, 1)); ?></div>
                <?php endif; ?>
                </div>
            </div>
        </div>
    </header>
           
    <div class="container">
        <div class="tab-container">
            <div class="tab-nav">
                <button class="tab-btn active" onclick="window.location.href='safetymeasure.php'">Safety Measures</button>
                <button class="tab-btn" onclick="window.location.href='reportissue.php'">Report Issues</button>
                <button class="tab-btn" onclick="window.location.href='update_profile_plant.php'">Update Profile</button>
                <button class="tab-btn" onclick="window.location.href='logout.php'">Logout</button>
            </div>
            <div class="tab-content active">
                <div class="content">
                    <?php
                // Database connection
                $servername = 'localhost';
                $username = 'root';
                $password = '';
                $db = 'log_book';

                $conn = mysqli_connect($servername, $username, $password, $db);

                // Check connection
                if (!$conn) {
                    die("<div class='message error'>Connection failed: " . mysqli_connect_error() . "</div>");
                }

                // Fetch Plant Supervisors from signup_table
                $plantSupervisors = [];
                $query = "SELECT full_name FROM signup_table WHERE role = 'Plant Supervisor' AND approved='approved'";
                $result = $conn->query($query);
                if ($result) {
                    while ($row = $result->fetch_assoc()) {
                        $plantSupervisors[] = $row['full_name'];
                    }
                    $result->free();
                } else {
                    echo "<div class='message error'>Error fetching Plant Supervisors: " . $conn->error . "</div>";
                }

                // Initialize message
                $message = "";

                // Process form submission
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    // Sanitize inputs
                    $measure_title = $conn->real_escape_string($_POST['measureTitle']);
                    $machine_id = $conn->real_escape_string($_POST['machineId']);
                    $location = $conn->real_escape_string($_POST['safetyLocation']);
                    $responsible_person = $conn->real_escape_string($_POST['responsiblePerson']);
                    $priority = $conn->real_escape_string($_POST['priority']);

                    // Server-side validation
                    if (empty($measure_title) || empty($machine_id) || empty($location) || empty($responsible_person) || empty($priority)) {
                        $message = "<div class='message error'>Error: All fields are required.</div>";
                    } else {
                        // Check if machine_id already exists (if you want to enforce uniqueness)
                        $checkQuery = "SELECT * FROM safety_measures WHERE machine_id = '$machine_id'";
                        $checkResult = $conn->query($checkQuery);
                        if ($checkResult->num_rows > 0) {
                            $message = "<div class='message error'>Error: Machine ID '$machine_id' already exists.</div>";
                        } else {
                            // Insert query
                            $sql = "INSERT INTO safety_measures (measure_title, machine_id, location, responsible_person, priority_level)
                                    VALUES ('$measure_title', '$machine_id', '$location', '$responsible_person', '$priority')";

                            // Execute query
                            if ($conn->query($sql)) {
                                $message = "<div class='message success'>Safety measure documented successfully!</div>";
                            } else {
                                $message = "<div class='message error'>Error: " . $conn->error . "</div>";
                            }
                            header("Refresh:3; url=safetymeasure.php"); // Increased delay to 3 seconds for better UX
                        }
                    }
                }

                // Display message
                echo $message;
                ?>

                <!-- Safety Measures Tab Content -->
                    <h1 class="section-title">Document Safety Measures</h1>

                    <div class="card">
                        <div class="card-header">
                            <h2 class="card-title">Safety Measure Documentation</h2>
                            <p class="card-description">Record safety measures implemented for machine errors</p>
                        </div>
                        <div class="card-content">
                            <form method="POST" action="">
                                <div class="form-grid">
                                    <div class="form-group">
                                        <label for="measureTitle" class="form-label">Safety Measure Title</label>
                                        <input type="text" id="measureTitle" name="measureTitle" class="form-input" placeholder="Enter Safety Measure Title" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="machineId" class="form-label">Machine ID</label>
                                        <input type="text" id="machineId" name="machineId" class="form-input" placeholder="Related Machine Identifier" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="safetyLocation" class="form-label">Location</label>
                                    <input type="text" id="safetyLocation" name="safetyLocation" class="form-input" placeholder="Plant Name / Area / Unit" required>
                                </div>

                                <div class="form-grid">
                                    <div class="form-group">
                                        <label for="responsiblePerson" class="form-label">Responsible Person</label>
                                        <select id="responsiblePerson" name="responsiblePerson" class="form-input" required>
                                            <option value="">Select a Plant Supervisor</option>
                                            <?php
                                            // Populate dropdown with Plant Supervisors
                                            foreach ($plantSupervisors as $supervisor) {
                                                echo "<option value='" . htmlspecialchars($supervisor) . "'>" . htmlspecialchars($supervisor) . "</option>";
                                            }
                                            ?>
                                        </select>
                                        <?php
                                        // Display a message if no Plant Supervisors are found
                                        if (empty($plantSupervisors)) {
                                            echo "<p class='message error'>No Plant Supervisors found. Please add a Plant Supervisor in the system.</p>";
                                        }
                                        ?>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="priority" class="form-label">Priority Level</label>
                                    <select id="priority" name="priority" class="form-input" required>
                                        <option value="">Select priority level</option>
                                        <option value="low">Low</option>
                                        <option value="medium">Medium</option>
                                        <option value="high">High</option>
                                        <option value="critical">Critical</option>
                                    </select>
                                </div>

                                <div class="card-footer">
                                    <button type="button" class="button button-outline" onclick="window.location.href='Plantsupervisor.html'">Cancel</button>
                                    <button type="submit" class="button button-primary" <?php echo empty($plantSupervisors) ? 'disabled' : ''; ?>>Document Safety Measure</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php mysqli_close($conn); ?>
</body>
</html>