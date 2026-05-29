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
    <title>GSEC Daily Work Logbook - Resolve Issue</title>
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
            <div class="user-info">Shift Engineer: <span>
                <?php
                $name = $_SESSION['username'] ?? 'Guest';
                echo htmlspecialchars($name);

                $conn = mysqli_connect("localhost", "root", "", "log_book");
                if (!$conn) {
                    die("<div class='error-message'>Connection failed: " . mysqli_connect_error() . "</div>");
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
                <h2 class="section-title">Resolve Issues in Instruments</h2>
                
                <div class="tab-container">
                    <div class="tab-nav">
                        <button class="tab-btn" onclick="window.location.href='report-new-issue.php'">Report New Issue</button>
                        <button class="tab-btn active">Resolve Issues</button>
                        <button class="tab-btn" onclick="window.location.href='recent-issues.php'">Pending Issues</button>
                        <button class="tab-btn" onclick="window.location.href='resolved-issues.php'">Solved Issues</button>
                        <button class="tab-btn" onclick="window.location.href='req_plan.php'">Plant Supervisor Request</button>
                        <button class="tab-btn" onclick="window.location.href='update_profile.php'">Update Profile</button>
                        <button class="tab-btn" onclick="window.location.href='logout.php'">Logout</button>
                    </div>
                    
                    <div class="tab-content active">
                        <div class="form-container">
                            <?php
                            $issue = null;
                            $selectedIssueID = isset($_POST['issue_id']) ? $_POST['issue_id'] : '';

                            if (isset($_POST['resolve'])) {
                                if (empty($_POST['issue_id'])) {
                                    echo "<div class='error-message'>Please select an instrument to resolve.</div>";
                                } else {
                                    $issueID = mysqli_real_escape_string($conn, $_POST['issue_id']);
                                    $solutionDetails = mysqli_real_escape_string($conn, $_POST['solution_details']);
                                    $solvedBy = mysqli_real_escape_string($conn, $_POST['solved_by']);

                                    if (!empty($solutionDetails) && !empty($solvedBy)) {
                                        $updateQuery = "UPDATE instrumentissues 
                                                        SET Status = 'Resolved',
                                                            solution_details = '$solutionDetails',
                                                            solved_by = '$solvedBy',
                                                            solution_date = NOW()
                                                        WHERE IssueID = '$issueID' AND Status = 'Faulty'";
                                        
                                        if (mysqli_query($conn, $updateQuery)) {
                                            if (mysqli_affected_rows($conn) > 0) {
                                                echo "<div class='success-message'>Issue #$issueID has been marked as resolved!</div>";
                                                $selectedIssueID = '';
                                                $issue = null;
                                            } else {
                                                $checkStatusQuery = "SELECT Status FROM instrumentissues WHERE IssueID = '$issueID'";
                                                $checkStatusResult = mysqli_query($conn, $checkStatusQuery);
                                                if ($checkStatusResult && mysqli_num_rows($checkStatusResult) > 0) {
                                                    $row = mysqli_fetch_assoc($checkStatusResult);
                                                    $currentStatus = $row['Status'];
                                                    echo "<div class='error-message'>Issue #$issueID is not in Faulty status. Current status: $currentStatus.</div>";
                                                } else {
                                                    echo "<div class='error-message'>Issue #$issueID does not exist.</div>";
                                                }
                                            }
                                        } else {
                                            echo "<div class='error-message'>Error updating issue: " . mysqli_error($conn) . "</div>";
                                        }
                                    } else {
                                        echo "<div class='error-message'>Please fill in all resolution details.</div>";
                                    }
                                }
                            }

                            $query = "SELECT IssueID, InstrumentName FROM instrumentissues WHERE Status = 'Faulty'";
                            $result = mysqli_query($conn, $query);

                            if (!$result) {
                                echo "<div class='error-message'>Error fetching instruments: " . mysqli_error($conn) . "</div>";
                            }

                            $hasFaultyIssues = mysqli_num_rows($result) > 0;
                            ?>

                            <form action="" method="POST">
                                <input type="hidden" name="form_type" value="resolve_issue">
                                <div class="form-group">
                                    <label for="instrumentDropdown">Select Instrument</label>
                                    <select class="form-control" id="instrumentDropdown" name="issue_id" onchange="if(this.value !== '') this.form.submit()">
                                        <option value="">-- Select an Instrument --</option>
                                        <?php
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            $issueID = $row['IssueID'];
                                            $instrumentName = $row['InstrumentName'];
                                            $selected = ($issueID == $selectedIssueID) ? 'selected' : '';
                                            echo "<option value='$issueID' $selected>$instrumentName (ID: $issueID)</option>";
                                        }
                                        ?>
                                    </select>
                                    <?php
                                    if (!$hasFaultyIssues) {
                                        echo "<div class='error-message'>No faulty issues available to resolve.</div>";
                                    }
                                    ?>
                                </div>

                                <?php
                                if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['issue_id']) && !empty($_POST['issue_id']) && !isset($_POST['resolve'])) {
                                    $selectedIssueID = mysqli_real_escape_string($conn, $_POST['issue_id']);
                                    $detailsQuery = "SELECT * FROM instrumentissues WHERE IssueID = '$selectedIssueID'";
                                    $detailsResult = mysqli_query($conn, $detailsQuery);

                                    if ($detailsResult && mysqli_num_rows($detailsResult) > 0) {
                                        $issue = mysqli_fetch_assoc($detailsResult);
                                        echo "<div class='details-container'>";
                                        echo "<h3>Instrument Issue Details</h3>";
                                        echo "<p><strong>Issue ID:</strong> " . htmlspecialchars($issue['IssueID']) . "</p>";
                                        echo "<p><strong>Instrument ID:</strong> " . htmlspecialchars($issue['InstrumentID']) . "</p>";
                                        echo "<p><strong>Instrument Name:</strong> " . htmlspecialchars($issue['InstrumentName']) . "</p>";
                                        echo "<p><strong>Issue Description:</strong> " . htmlspecialchars($issue['IssueDescription']) . "</p>";
                                        echo "<p><strong>Severity:</strong> " . htmlspecialchars($issue['Severity']) . "</p>";
                                        echo "<p><strong>Location:</strong> " . htmlspecialchars($issue['Location']) . "</p>";
                                        echo "<p><strong>Action Taken:</strong> " . htmlspecialchars($issue['ActionTaken']) . "</p>";
                                        echo "<p><strong>Reported By:</strong> " . htmlspecialchars($issue['ReportedBy']) . "</p>";
                                        echo "<p><strong>Status:</strong> " . htmlspecialchars($issue['Status']) . "</p>";
                                        echo "<p><strong>Reported Date:</strong> " . htmlspecialchars($issue['ReportedDate']) . "</p>";
                                        echo "</div>";
                                    } else {
                                        echo "<div class='error-message'>No details found for the selected issue (IssueID: $selectedIssueID).</div>";
                                    }
                                }

                                if (isset($issue) && $issue['Status'] == 'Faulty' && !isset($_POST['resolve'])) {
                                    echo "<div id='resolutionForm'>";
                                    echo "<div class='form-group'>";
                                    echo "<br><label for='solution-details'>Solution Details</label>";
                                    echo "<textarea class='form-control' id='solution-details' name='solution_details' placeholder='Describe how the issue was resolved...' required></textarea>";
                                    echo "</div>";
                                    echo "<div class='form-group'>";
                                    echo "<label for='solved-by'>Solved By</label>";
                                    echo "<input type='text' class='form-control' id='solved-by' name='solved_by' placeholder='Enter your name' required>";
                                    echo "</div>";
                                    echo "<button class='btn btn-success' type='submit' name='resolve'>Mark as Resolved</button>";
                                    echo "</div>";
                                }
                                ?>
                            </form>

                            <?php mysqli_close($conn); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>