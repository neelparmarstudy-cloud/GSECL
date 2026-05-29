<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

// Database connection settings
$servername = 'localhost';
$username = 'root';
$password = '';
$dbname = 'log_book';

// Create connection using mysqli
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check if connection failed
if (!$conn) {
    die("<div class='error-message'>Connection failed: " . mysqli_connect_error() . "</div>");
}

// Initialize messages
$success_message = '';
$error_message = '';

// Check for a success message in the session (set after redirect)
if (isset($_SESSION['success_message'])) {
    $success_message = $_SESSION['success_message'];
    unset($_SESSION['success_message']); // Clear the message after displaying
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data and sanitize inputs
    $instrument_id = mysqli_real_escape_string($conn, $_POST['instrument_id']);
    $instrument_name = mysqli_real_escape_string($conn, $_POST['instrument_name']);
    $issue_description = mysqli_real_escape_string($conn, $_POST['issue_description']);
    $severity = mysqli_real_escape_string($conn, $_POST['severity']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $action_taken = mysqli_real_escape_string($conn, $_POST['action_taken']);
    $reported_by = mysqli_real_escape_string($conn, $_POST['reported_by']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    // Validate that all required fields are filled
    if (empty($instrument_id) || empty($instrument_name) || empty($issue_description) || empty($severity) || empty($reported_by) || empty($status)) {
        $error_message = "Please fill all required fields.";
    } else {
        // Check if INSTRUMENTID already exists
        $check_query = "SELECT * FROM instrumentissues WHERE INSTRUMENTID = '$instrument_id'";
        $check_result = mysqli_query($conn, $check_query);
        if (mysqli_num_rows($check_result) > 0) {
            $error_message = "Instrument ID '$instrument_id' already exists.";
        } else {
            // Insert data into instrumentissues table
            $sql = "INSERT INTO instrumentissues (
                INSTRUMENTID, 
                INSTRUMENTNAME, 
                ISSUEDESCRIPTION, 
                SEVERITY, 
                LOCATION, 
                ACTIONTAKEN, 
                REPORTEDBY, 
                STATUS,
                REPORTEDDATE,
                SOLUTION_DETAILS,
                SOLVED_BY,
                SOLUTION_DATE
            ) VALUES (
                '$instrument_id',
                '$instrument_name',
                '$issue_description',
                '$severity',
                '$location',
                '$action_taken',
                '$reported_by',
                '$status',
                CURRENT_TIMESTAMP,
                '',
                '',
                CURRENT_TIMESTAMP
            )";

            // Execute the query
            if (mysqli_query($conn, $sql)) {
                // Store the success message in the session
                $_SESSION['success_message'] = "Issue reported successfully!";
                // Redirect to the same page using PRG pattern
                header("Location: report-new-issue.php");
                exit();
            } else {
                $error_message = "Error reporting issue: " . mysqli_error($conn);
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
    <title>GSEC Daily Work Logbook - Report New Issue</title>
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
            min-height: 100vh;
            background: linear-gradient(135deg, rgba(255,255,255,0.9) 0%, rgba(240,240,240,0.9) 100%);
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
    <script>
        // Prevent multiple form submissions
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            form.addEventListener('submit', function() {
                const submitButton = form.querySelector('button[type="submit"]');
                submitButton.disabled = true;
                submitButton.innerText = 'Submitting...';
            });
        });
    </script>
</head>
<body>
    <div class="container">
        <header>
            <div class="company-name">GUJARAT STATE ELECTRICITY CORPORATION LIMITED</div>
            <div class="user-info">
                User: <span>
                <?php
                $name = $_SESSION['username'];
                echo htmlspecialchars($name);

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
        </header>
        
        <div class="main-content">
            <div class="content">
                <h2 class="section-title">Report issues in Instruments</h2>
                
                <div class="tab-container">
                    <div class="tab-nav">
                        <button class="tab-btn active">Report New Issue</button>
                        <button class="tab-btn" onclick="window.location.href='resolve-issue.php'">Resolve Issues</button>
                        <button class="tab-btn" onclick="window.location.href='recent-issues.php'">Pending Issues</button>
                        <button class="tab-btn" onclick="window.location.href='resolved-issues.php'">Solved Issues</button>
                        <button class="tab-btn" onclick="window.location.href='req_plan.php'">Plant Supervisor Request</button>
                        <button class="tab-btn" onclick="window.location.href='update_profile.php'">Update Profile</button>
                        <button class="tab-btn" onclick="window.location.href='logout.php'">Logout</button>
                    </div>
                    
                    <div class="tab-content active">
                        <div class="form-container">
                            <!-- Display success or error message -->
                            <?php if ($success_message): ?>
                                <div class="success-message"><?php echo $success_message; ?></div>
                            <?php endif; ?>
                            <?php if ($error_message): ?>
                                <div class="error-message"><?php echo $error_message; ?></div>
                            <?php endif; ?>

                            <form action="" method="POST">
                                <div class="form-row">
                                    <div class="form-col">
                                        <div class="form-group">
                                            <label for="instrument-id">Instrument ID</label>
                                            <input type="text" class="form-control" id="instrument-id" name="instrument_id" placeholder="Enter instrument ID" required>
                                        </div>
                                    </div>
                                    <div class="form-col">
                                        <div class="form-group">
                                            <label for="instrument-name">Instrument Name</label>
                                            <input type="text" class="form-control" id="instrument-name" name="instrument_name" placeholder="Enter instrument name" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="issue-description">Issue Description</label>
                                    <textarea class="form-control" id="issue-description" name="issue_description" placeholder="Describe the issue in detail..." required></textarea>
                                </div>
                                <div class="form-row">
                                    <div class="form-col">
                                        <div class="form-group">
                                            <label for="issue-severity">Severity</label>
                                            <select class="form-control" id="issue-severity" name="severity" required>
                                                <option value="">Select Severity</option>
                                                <option value="low">Low</option>
                                                <option value="medium">Medium</option>
                                                <option value="high">High</option>
                                                <option value="critical">Critical</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-col">
                                        <div class="form-group">
                                            <label for="issue-location">Location</label>
                                            <input type="text" class="form-control" id="issue-location" name="location" placeholder="Enter location">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="action-taken">Action Taken (if any)</label>
                                    <textarea class="form-control" id="action-taken" name="action_taken" placeholder="Describe any immediate action taken..."></textarea>
                                </div>
                                <div class="form-row">
                                    <div class="form-col">
                                        <div class="form-group">
                                            <label for="reported-by">Reported By</label>
                                            <input type="text" class="form-control" id="reported-by" name="reported_by" value="<?php echo htmlspecialchars($_SESSION['username']); ?>" readonly required>
                                        </div>
                                    </div>
                                    <div class="form-col">
                                        <div class="form-group">
                                            <label for="status">Status</label>
                                            <input type="text" class="form-control" id="status" name="status" value="Faulty" readonly>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <br>
                                <button class="btn btn-primary" type="submit">Submit Issue Report</button>
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