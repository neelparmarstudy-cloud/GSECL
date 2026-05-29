<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

// Step 1: Connect to the database
$servername = "localhost";
$username = "root";
$password = "";
$database = "log_book";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("<div class='error-message'>Connection failed: " . $conn->connect_error . "</div>");
}

// Step 2: Get the ISSUEID from the URL
if (!isset($_GET['issue_id']) || empty($_GET['issue_id'])) {
    die("<div class='error-message'>No issue ID provided.</div>");
}

$issue_id = mysqli_real_escape_string($conn, $_GET['issue_id']);

// Step 3: Query the uploads table for files associated with the ISSUEID
$sql = "SELECT * FROM uploads WHERE ISSUEID = '$issue_id'";
$result = $conn->query($sql);

if (!$result) {
    echo "<div class='error-message'>Error fetching files: " . $conn->error . "</div>";
    $conn->close();
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GSEC Daily Work Logbook - View Files</title>
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
            background-image: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMDAlIiBoZWlnaHQ9IjEwMCUiPjxkZWZzPjxwYXR0ZXJuIGlkPSJwYXR0ZXJuIiBwYXR0ZXJuVW5pdHM9InVzZXJTcGFjZU9uVXNlIiB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHBhdHRlcm5UcmFuc2Zvcm09InJvdGF0ZSg4NSkiPjxsaW5lIHgxPSIwIiB5PSIwIiB4Mj0iMCIgeTI9IjQwIiBzdHJva2U9IiNlMGUwZTAiIHN0cm9rZS13aWR0aD0iMSIvPjwvcGF0dGVybj48L2RlZnM+PHJlY3QgZmlsbD0idXJsKCNwYXR0ZXJuKSIgaGVpZ2h0PSIxMDAlIiB3aWR0aD0iMTAwJSIvPjwvc3ZnPg==');
            background-repeat: repeat;
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
        
        .error-message {
            color: #dc3545;
            margin-bottom: 15px;
            font-weight: bold;
        }
        
        .file-preview img, .file-preview video {
            max-width: 300px;
            margin: 10px 0;
            cursor: pointer; /* Indicate the image is clickable */
        }
        
        a {
            text-decoration: none;
            color: blue;
        }
    </style>
    <script>
        // Function to toggle full-screen mode for an element
        function toggleFullScreen(element) {
            if (!document.fullscreenElement) {
                // Enter full-screen mode
                element.requestFullscreen().catch(err => {
                    console.error(`Error attempting to enable full-screen mode: ${err.message} (${err.name})`);
                });
            } else {
                // Exit full-screen mode
                document.exitFullscreen();
            }
        }

        // Add event listeners to all images after the DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            const images = document.querySelectorAll('.file-preview img');
            images.forEach(image => {
                image.addEventListener('click', function() {
                    toggleFullScreen(image);
                });
            });
        });
    </script>
</head>
<body>
    <div class="container">
        <header>
            <div class="company-name">GUJARAT STATE ELECTRICITY CORPORATION LIMITED</div>
            <div class="user-info">Shift Engineer: <span>
                <?php echo htmlspecialchars($_SESSION['username']); ?>
            </span></div>
        </header>
        
        <div class="main-content">
            <div class="content">
                <h2 class="section-title">Files for Issue #<?php echo htmlspecialchars($issue_id); ?></h2>
                
                <?php
                if ($result->num_rows > 0) {
                    echo "<table class='data-table'>";
                    echo "<thead>";
                    echo "<tr>";
                    echo "<th>Instrument ID</th>";
                    echo "<th>File Image</th>";
                    echo "<th>File Video</th>";
                    echo "</tr>";
                    echo "</thead>";
                    echo "<tbody>";

                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['INSTRUMENTID']) . "</td>";
                        
                        // Display File Image (view only, clickable for full-screen)
                        echo "<td>";
                        if (!empty($row['FILEIMAGE']) && $row['FILEIMAGE'] !== 'NULL') {
                            echo "<div class='file-preview'>";
                            echo "<img src='uploads/" . htmlspecialchars($row['FILEIMAGE']) . "' alt='File Image'>";
                            echo "</div>";
                        } else {
                            echo "No image available";
                        }
                        echo "</td>";
                        
                        // Display File Video (play only, with full-screen option via controls)
                        echo "<td>";
                        if (!empty($row['FILEVIDEO']) && $row['FILEVIDEO'] !== 'NULL') {
                            echo "<div class='file-preview'>";
                            echo "<video controls>";
                            echo "<source src='uploads/" . htmlspecialchars($row['FILEVIDEO']) . "' type='video/mp4'>";
                            echo "Your browser does not support the video tag.";
                            echo "</video>";
                            echo "</div>";
                        } else {
                            echo "No video available";
                        }
                        echo "</td>";
                        echo "</tr>";
                    }

                    echo "</tbody>";
                    echo "</table>";
                } else {
                    echo "<p>No files found for Issue #$issue_id.</p>";
                }

                // Step 4: Close the database connection
                $conn->close();
                ?>
                
                <button class="btn btn-primary" onclick="window.location.href='req_plan.php'">Back to Plant Supervisor Request</button>
            </div>
        </div>
    </div>
</body>
</html>