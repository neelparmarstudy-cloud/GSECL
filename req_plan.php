<?php
session_start();

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
    <title>GSEC Daily Work Logbook - Plant Supervisor Request</title>
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
        
        .btn-warning {
            background-color: #ffc107;
            color: #333;
        }
        
        .btn-success {
            background-color: #28a745;
            color: white;
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
</head>
<body>
    <div class="container">
        <header>
            <div class="company-name">GUJARAT STATE ELECTRICITY CORPORATION LIMITED</div>
            <div class="user-info">Shift Engineer: <span>
                <?php
                $name = $_SESSION['username'] ?? 'Guest';
                echo htmlspecialchars($name);

                $conn = mysqli_connect("localhost", "root", "", "log_book");
                if (!$conn) {
                    die("Connection failed: " . mysqli_connect_error());
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
        </header>
        
        <div class="main-content">
            <div class="content">
                <h2 class="section-title">Plant Supervisor Request</h2>
                
                <div class="tab-container">
                    <div class="tab-nav">
                        <button class="tab-btn" onclick="window.location.href='report-new-issue.php'">Report New Issue</button>
                        <button class="tab-btn" onclick="window.location.href='resolve-issue.php'">Resolve Issues</button>
                        <button class="tab-btn" onclick="window.location.href='recent-issues.php'">Pending Issues</button>
                        <button class="tab-btn" onclick="window.location.href='resolved-issues.php'">Solved Issues</button>
                        <button class="tab-btn active">Plant Supervisor Request</button>
                        <button class="tab-btn" onclick="window.location.href='update_profile.php'">Update Profile</button>
                        <button class="tab-btn" onclick="window.location.href='logout.php'">Logout</button>
                    </div>

                    <div class="tab-content active">
                        <?php
                        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['resolve_issue'])) {
                            $issueID = mysqli_real_escape_string($conn, $_POST['issue_id']);
                            $updateQuery = "UPDATE issue_plant SET STATUS = 'Resolved' WHERE ISSUEID = '$issueID'";
                            if (mysqli_query($conn, $updateQuery)) {
                                if (mysqli_affected_rows($conn) > 0) {
                                    echo "<div class='success-message'>Issue #$issueID has been marked as resolved!</div>";
                                } else {
                                    echo "<div class='error-message'>Issue #$issueID could not be updated. It may already be resolved or does not exist.</div>";
                                }
                            } else {
                                echo "<div class='error-message'>Error updating issue: " . mysqli_error($conn) . "</div>";
                            }
                        }

                        $query = "SELECT * FROM issue_plant WHERE SHIFT_ENG_USERNAME ='$name'";
                        $result = mysqli_query($conn, $query);

                        if (!$result) {
                            echo "<p>Error fetching issues: " . mysqli_error($conn) . "</p>";
                        } else {
                            if (mysqli_num_rows($result) > 0) {
                                echo "<table class='data-table'>";
                                echo "<thead>";
                                echo "<tr>";
                                echo "<th>Issue ID</th>";
                                echo "<th>Shift Engineer Name</th>";
                                echo "<th>Shift Engineer Email</th>";
                                echo "<th>Instrument Name</th>";
                                echo "<th>Instrument ID</th>";
                                echo "<th>Location</th>";
                                echo "<th>Issue Description</th>";
                                echo "<th>Issue Time</th>";
                                echo "<th>Rating</th>";
                                echo "<th>Plant S/N</th>";
                                echo "<th>Status</th>";
                                echo "<th>Files</th>";
                                echo "<th>Action</th>";
                                echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";

                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<tr>";
                                    echo "<td>" . htmlspecialchars($row['ISSUEID']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['SHIFT_ENG_NAME']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['SHIFT_ENG_EMAIL']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['INSTRUMENTNAME']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['INSTRUMENTID']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['LOCATION']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['ISSUEDESCRIPTION']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['ISSUETIME']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['RATING']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['PLANT_S_N']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['STATUS']) . "</td>";
                                    echo "<td>";
                                    echo "<button class='btn btn-success' onclick=\"window.location.href='files.php?issue_id=" . htmlspecialchars($row['ISSUEID']) . "'\">Files</button>";
                                    echo "</td>";
                                    echo "<td>";
                                    echo "<form method='POST' action=''>";
                                    echo "<input type='hidden' name='issue_id' value='" . htmlspecialchars($row['ISSUEID']) . "'>";
                                    echo "<button type='submit' name='resolve_issue' class='btn btn-success'>Submit</button>";
                                    echo "</form>";
                                    echo "</td>";
                                    echo "</tr>";
                                }

                                echo "</tbody>";
                                echo "</table>";
                            } else {
                                echo "<p>No issues found in the issue_plant table.</p>";
                            }
                        }

                        mysqli_close($conn);
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>