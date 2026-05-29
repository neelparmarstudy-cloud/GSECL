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
    <title>GSEC Daily Work Logbook - Resolved Issues</title>
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

                $conn = new mysqli('localhost', 'root', '', 'log_book');
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                $photo_query = "SELECT photo FROM user_photo WHERE username = '$name'";
                $photo_result = $conn->query($photo_query);
                $photo_path = '';
                if ($photo_result && $photo_result->num_rows > 0) {
                    $photo_row = $photo_result->fetch_assoc();
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
                <h2 class="section-title">Solved Issues in Instruments</h2>
                
                <div class="tab-container">
                    <div class="tab-nav">
                        <button class="tab-btn" onclick="window.location.href='report-new-issue.php'">Report New Issue</button>
                        <button class="tab-btn" onclick="window.location.href='resolve-issue.php'">Resolve Issues</button>
                        <button class="tab-btn" onclick="window.location.href='recent-issues.php'">Pending Issues</button>
                        <button class="tab-btn active">Solved Issues</button>
                        <button class="tab-btn" onclick="window.location.href='req_plan.php'">Plant Supervisor Request</button>
                        <button class="tab-btn" onclick="window.location.href='update_profile.php'">Update Profile</button>
                        <button class="tab-btn" onclick="window.location.href='logout.php'">Logout</button>
                    </div>
                    
                    <div class="tab-content active">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Issue ID</th>
                                    <th>Instrument</th>
                                    <th>Description</th>
                                    <th>Severity</th>
                                    <th>Resolved On</th>
                                    <th>Resolved By</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql = "SELECT IssueID, InstrumentName, IssueDescription, Severity, Solution_date, Solved_by FROM instrumentissues WHERE Status IN ('Resolved')";
                                $result = $conn->query($sql);

                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<tr>";
                                        echo "<td>" . htmlspecialchars($row["IssueID"]) . "</td>";
                                        echo "<td>" . htmlspecialchars($row["InstrumentName"]) . "</td>";
                                        echo "<td>" . htmlspecialchars($row["IssueDescription"]) . "</td>";
                                        echo "<td>" . htmlspecialchars($row["Severity"]) . "</td>";
                                        echo "<td>" . htmlspecialchars($row["Solution_date"]) . "</td>";
                                        echo "<td>" . htmlspecialchars($row["Solved_by"]) . "</td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='7'>No solved or resolved issues found.</td></tr>";
                                }

                                $conn->close();
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>