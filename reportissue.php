<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Issue</title>
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
                <div class="user-info">
                    <div class="user-info">Plant Supervisor: <span>
                        <?php
                        $name = $_SESSION['username'] ?? 'Guest';
                        echo htmlspecialchars($name);

                        // Database connection
                        $servername = 'localhost';
                        $username = 'root';
                        $password = '';
                        $db = 'log_book';

                        $conn = mysqli_connect($servername, $username, $password, $db);
                        if (!$conn) {
                            die("<div class='message error'>Connection failed: " . mysqli_connect_error() . "</div>");
                        }

                        // Fetch the user's photo
                        $current_username = $_SESSION['username'];
                        $photo_query = "SELECT photo FROM user_photo WHERE username = '$current_username'";
                        $photo_result = mysqli_query($conn, $photo_query);

                        $photo_path = '';
                        if ($photo_result) {
                            if (mysqli_num_rows($photo_result) > 0) {
                                $photo_row = mysqli_fetch_assoc($photo_result);
                                $photo_filename = $photo_row['photo']; // Get the filename from the database
                                $photo_path = 'signup/' . $photo_filename; // Construct the full path
                            } else {
                                // Fallback to default image based on username
                                $photo_path = 'signup/' . $current_username . '.jpg';
                            }
                        } else {
                            // Fallback to default image if query fails
                            $photo_path = 'signup/' . $current_username . '.jpg';
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
        </div>
    </header>

    <!-- Rest of your existing code remains unchanged -->
    <div class="container">
        <div class="tab-container">
            <div class="tab-nav">
                <button class="tab-btn" onclick="window.location.href='safetymeasure.php'">Safety Measures</button>
                <button class="tab-btn active" onclick="window.location.href='reportissue.php'">Report Issues</button>
                <button class="tab-btn" onclick="window.location.href='update_profile_plant.php'">Update Profile</button>
                <button class="tab-btn" onclick="window.location.href='logout.php'">Logout</button>
            </div>
            <div class="tab-content active">
                <div class="content">

                    <?php
                    // Your existing PHP logic for form handling
                    if (!$conn) {
                        die("<div class='message error'>Connection failed: " . mysqli_connect_error() . "</div>");
                    }

                    // Fetch Shift Engineers with username
                    $shiftEngineers = [];
                    $query = "SELECT full_name, email, username FROM signup_table WHERE role = 'Shift Engineer' AND approved='approved'";
                    $result = $conn->query($query);
                    if ($result) {
                        while ($row = $result->fetch_assoc()) {
                            $shiftEngineers[] = [
                                'name' => $row['full_name'],
                                'email' => $row['email'],
                                'username' => $row['username']
                            ];
                        }
                        $result->free();
                    } else {
                        echo "<div class='message error'>Error fetching Shift Engineers: " . $conn->error . "</div>";
                    }

                    // Fetch the current Plant Supervisor's full name based on session username
                    $currentSupervisor = '';
                    $query = "SELECT full_name FROM signup_table WHERE username = '" . $conn->real_escape_string($_SESSION['username']) . "' AND role = 'Plant Supervisor' AND approved='approved'";
                    $result = $conn->query($query);
                    if ($result && $result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        $currentSupervisor = $row['full_name'];
                        $result->free();
                    } else {
                        $currentSupervisor = 'Unknown Supervisor';
                        echo "<div class='message error'>Error fetching current Plant Supervisor: " . $conn->error . "</div>";
                    }

                    $message = "";
                    // Your existing form processing logic remains unchanged
                    if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST)) {
                        $shift_eng_name = $conn->real_escape_string($_POST['engineerName'] ?? '');
                        $shift_eng_email = $conn->real_escape_string($_POST['engineerEmail'] ?? '');
                        $shift_eng_username = $conn->real_escape_string($_POST['engineerUsername'] ?? '');
                        $instrument_name = $conn->real_escape_string($_POST['reportProblemName'] ?? '');
                        $instrument_id = $conn->real_escape_string($_POST['reportProblemId'] ?? '');
                        $location = $conn->real_escape_string($_POST['reportLocation'] ?? '');
                        $issue_description = $conn->real_escape_string($_POST['reportDescription'] ?? '');
                        $rating = $conn->real_escape_string($_POST['reportRating'] ?? '');
                        $plant_s_n = $conn->real_escape_string($_POST['plantSupervisor'] ?? '');
                        $status = "Pending";

                        if (empty($shift_eng_name) || empty($shift_eng_email) || empty($shift_eng_username) || empty($instrument_name) || empty($instrument_id) || empty($location) || empty($issue_description) || empty($rating) || empty($plant_s_n)) {
                            $message = "<div class='message error'>Error: All fields are required.</div>";
                        } else {
                            $checkQuery = "SELECT * FROM issue_plant WHERE INSTRUMENTID = '$instrument_id'";
                            $checkResult = $conn->query($checkQuery);
                            if ($checkResult->num_rows > 0) {
                                $message = "<div class='message error'>Error: Instrument ID '$instrument_id' already exists.</div>";
                            } else {
                                $sql = "INSERT INTO issue_plant (SHIFT_ENG_NAME, SHIFT_ENG_EMAIL, SHIFT_ENG_USERNAME, INSTRUMENTNAME, INSTRUMENTID, LOCATION, ISSUEDESCRIPTION, RATING, PLANT_S_N, STATUS)
                                        VALUES ('$shift_eng_name', '$shift_eng_email', '$shift_eng_username', '$instrument_name', '$instrument_id', '$location', '$issue_description', '$rating', '$plant_s_n', '$status')";

                                if ($conn->query($sql)) {
                                    $message = "<div class='message success'>Issue reported successfully!</div>";

                                    $issueId = mysqli_insert_id($conn);

                                    $fileImage = null;
                                    $fileVideo = null;
                                    $uploadSuccess = false;

                                    $uploadDir = 'uploads/';
                                    if (!is_dir($uploadDir)) {
                                        mkdir($uploadDir, 0755, true);
                                    }

                                    if (!empty($_FILES['my_image']['name'])) {
                                        $img_name = $_FILES['my_image']['name'];
                                        $img_size = $_FILES['my_image']['size'];
                                        $tmp_name = $_FILES['my_image']['tmp_name'];
                                        $error = $_FILES['my_image']['error'];

                                        if ($error === 0) {
                                            if ($img_size > 50000000) {
                                                $message .= "<div class='message error'>Image file size is too large (max 5MB).</div>";
                                            } else {
                                                $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
                                                $img_ex_lc = strtolower($img_ex);
                                                $allowed_exs = array("jpg", "jpeg", "png");

                                                if (in_array($img_ex_lc, $allowed_exs)) {
                                                    $new_img_name = uniqid("IMG-", true) . '.' . $img_ex_lc;
                                                    $img_upload_path = $uploadDir . $new_img_name;
                                                    if (move_uploaded_file($tmp_name, $img_upload_path)) {
                                                        $fileImage = $new_img_name;
                                                        $uploadSuccess = true;
                                                    } else {
                                                        $message .= "<div class='message error'>Failed to move uploaded image.</div>";
                                                    }
                                                } else {
                                                    $message .= "<div class='message error'>Invalid image type. Allowed types: " . implode(", ", $allowed_exs) . "</div>";
                                                }
                                            }
                                        } else {
                                            $message .= "<div class='message error'>Image upload error: Error code " . $error . "</div>";
                                        }
                                    }

                                    if (!empty($_FILES['my_video']['name'])) {
                                        $video_name = $_FILES['my_video']['name'];
                                        $video_size = $_FILES['my_video']['size'];
                                        $tmp_name = $_FILES['my_video']['tmp_name'];
                                        $error = $_FILES['my_video']['error'];

                                        if ($error === 0) {
                                            if ($video_size > 500000000) {
                                                $message .= "<div class='message error'>Video file size is too large (max 50MB).</div>";
                                            } else {
                                                $video_ex = pathinfo($video_name, PATHINFO_EXTENSION);
                                                $video_ex_lc = strtolower($video_ex);
                                                $allowed_exs = array("mp4", "avi", "mov");

                                                if (in_array($video_ex_lc, $allowed_exs)) {
                                                    $new_video_name = uniqid("VID-", true) . '.' . $video_ex_lc;
                                                    $video_upload_path = $uploadDir . $new_video_name;
                                                    if (move_uploaded_file($tmp_name, $video_upload_path)) {
                                                        $fileVideo = $new_video_name;
                                                        $uploadSuccess = true;
                                                    } else {
                                                        $message .= "<div class='message error'>Failed to move uploaded video.</div>";
                                                    }
                                                } else {
                                                    $message .= "<div class='message error'>Invalid video type. Allowed types: " . implode(", ", $allowed_exs) . "</div>";
                                                }
                                            }
                                        } else {
                                            $message .= "<div class='message error'>Video upload error: Error code " . $error . "</div>";
                                        }
                                    }

                                    if ($uploadSuccess) {
                                        $fileImage = $fileImage ?? 'NULL';
                                        $fileVideo = $fileVideo ?? 'NULL';
                                        $sql = "INSERT INTO uploads (ISSUEID, INSTRUMENTID, FILEIMAGE, FILEVIDEO) 
                                                VALUES ('$issueId', '$instrument_id', " . ($fileImage === 'NULL' ? 'NULL' : "'$fileImage'") . ", " . ($fileVideo === 'NULL' ? 'NULL' : "'$fileVideo'") . ")";
                                        if ($conn->query($sql)) {
                                            $message .= "<div class='message success'>Files uploaded successfully!</div>";
                                        } else {
                                            $message .= "<div class='message error'>Error uploading to database: " . $conn->error . "</div>";
                                        }
                                    }
                                } else {
                                    $message = "<div class='message error'>Error: " . $conn->error . "</div>";
                                }
                            }
                        }
                    } elseif ($_SERVER["REQUEST_METHOD"] == "POST" && empty($_POST)) {
                        $message = "<div class='message error'>No form data received. Please check file size limits or form submission.</div>";
                    }

                    echo $message;
                    ?>

                    <div class="card">
                        <!-- Your existing form content remains unchanged -->
                        <div class="card-header">
                            <h2 class="card-title">Issue Report Form</h2>
                            <p class="card-description">Report machine issues to the responsible shift engineer</p>
                        </div>
                        <div class="card-content">
                            <form method="POST" action="reportissue.php" enctype="multipart/form-data">
                                <div class="form-grid">
                                    <div class="form-group">
                                        <label for="engineerName" class="form-label">Shift Engineer Name</label>
                                        <select id="engineerName" name="engineerName" class="form-input" onchange="updateFields(this)" required>
                                            <option value="">Select a Shift Engineer</option>
                                            <?php
                                            foreach ($shiftEngineers as $engineer) {
                                                echo "<option value='" . htmlspecialchars($engineer['name']) . "' 
                                                      data-email='" . htmlspecialchars($engineer['email']) . "' 
                                                      data-username='" . htmlspecialchars($engineer['username']) . "'>" 
                                                      . htmlspecialchars($engineer['name']) . "</option>";
                                            }
                                            ?>
                                        </select>
                                        <?php
                                        if (empty($shiftEngineers)) {
                                            echo "<p class='message error'>No Shift Engineers found. Please add a Shift Engineer in the system.</p>";
                                        }
                                        ?>
                                    </div>
                                    <div class="form-group">
                                        <label for="engineerEmail" class="form-label">Shift Engineer Email</label>
                                        <input type="email" id="engineerEmail" name="engineerEmail" class="form-input" placeholder="Email of the shift engineer" readonly required>
                                    </div>
                                    <div class="form-group">
                                        <label for="engineerUsername" class="form-label">Shift Engineer Username</label>
                                        <input type="text" id="engineerUsername" name="engineerUsername" class="form-input" placeholder="Username of the shift engineer" readonly required>
                                    </div>
                                </div>

                                <div class="form-grid">
                                    <div class="form-group">
                                        <label for="reportProblemName" class="form-label">Instrument Name</label>
                                        <input type="text" id="reportProblemName" name="reportProblemName" class="form-input" placeholder="Enter Instrument name" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="reportProblemId" class="form-label">Instrument ID</label>
                                        <input type="text" id="reportProblemId" name="reportProblemId" class="form-input" placeholder="Enter Instrument ID" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="reportLocation" class="form-label">Location</label>
                                    <input type="text" id="reportLocation" name="reportLocation" class="form-input" placeholder="Plant Name / Area / Unit" required>
                                </div>

                                <div class="form-group">
                                    <label for="reportDescription" class="form-label">Issue Description</label>
                                    <textarea id="reportDescription" name="reportDescription" class="form-input" placeholder="Brief Explanation of the Issue" required></textarea>
                                </div>

                                <div class="form-group">
                                    <label for="reportRating" class="form-label">Problem Rating</label>
                                    <select id="reportRating" name="reportRating" class="form-input" required>
                                        <option value="">Select a rating (1-4)</option>
                                        <option value="1">1 - Low</option>
                                        <option value="2">2 - Medium</option>
                                        <option value="3">3 - High</option>
                                        <option value="4">4 - Critical</option>
                                    </select>
                                </div>

                                <div class="form-grid">
                                    <div class="form-group">
                                        <label for="plantSupervisor" class="form-label">Reported by: Plant Supervisor</label>
                                        <input type="text" id="plantSupervisor" name="plantSupervisor" class="form-input" 
                                               value="<?php echo htmlspecialchars($currentSupervisor); ?>" readonly required>
                                    </div>
                                </div>
                                <div class="up">
                                    <div class="upload-group">
                                        <label for="upload">Upload Image</label>
                                        <input type="file" name="my_image" id="upload">
                                    </div>
                                    <div class="upload-group">
                                        <label for="upload_video">Upload Video</label>
                                        <input type="file" name="my_video" id="upload_video">
                                    </div>
                                </div>

                                <div class="card-footer">
                                    <button type="button" class="button button-outline" onclick="window.location.href='Plantsupervisor.html'">Cancel</button>
                                    <button type="submit" class="button button-primary" <?php echo (empty($shiftEngineers) || empty($currentSupervisor)) ? 'disabled' : ''; ?>>Report to Shift Engineer</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <script>
                        function updateFields(select) {
                            const emailField = document.getElementById('engineerEmail');
                            const usernameField = document.getElementById('engineerUsername');
                            const selectedOption = select.options[select.selectedIndex];
                            emailField.value = selectedOption.getAttribute('data-email') || '';
                            usernameField.value = selectedOption.getAttribute('data-username') || '';
                        }
                    </script>
                </div>
            </div>
        </div>
    </div>
    <?php
    $conn->close();
    ?>
</body>
</html>