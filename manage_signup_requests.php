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
    <title>Manage Sign-up Requests</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        h2 {
            font-size: 24px;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #f9f9f9;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        .actions {
            display: flex;
            gap: 10px;
        }
        .approve-btn, .reject-btn, .fired-btn {
            padding: 5px 15px;
            border: none;
            color: white;
            cursor: pointer;
            border-radius: 3px;
        }
        .approve-btn {
            background-color: #007bff;
        }
        .reject-btn {
            background-color: #dc3545;
        }
        .approve-btn:hover {
            background-color: #0056b3;
        }
        .reject-btn:hover {
            background-color: #c82333;
        }
        .fired-btn {
            background-color: rgb(252, 145, 23);
        }
        .fired-btn:hover {
            background-color: rgb(252, 145, 23);
        }
        .message {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 3px;
            font-weight: bold;
        }
        .success-message {
            background-color: #d4edda;
            color: #155724;
        }
        .error-message {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <h2>Manage Sign-up Requests</h2>

    <?php
    // Display success or error message if set
    if (isset($_SESSION['message'])) {
        $message_type = $_SESSION['message_type'] === 'success' ? 'success-message' : 'error-message';
        echo "<div class='message $message_type'>" . htmlspecialchars($_SESSION['message']) . "</div>";
        // Clear the message after displaying it
        unset($_SESSION['message']);
        unset($_SESSION['message_type']);
    }
    ?>

    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "log_book";

            // Create connection
            $conn = new mysqli($servername, $username, $password, $dbname);

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Fetch data from signup_table where approved is 'pending'
            $sql = "SELECT id, full_name, email, role FROM signup_table WHERE approved = 'pending'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // Output data of each row
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row["full_name"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["email"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["role"]) . "</td>";
                    echo "<td class='actions'>";
                    echo "<button class='approve-btn' onclick='handleAction(" . $row["id"] . ", \"approve\")'>Approve</button>";
                    echo "<button class='reject-btn' onclick='handleAction(" . $row["id"] . ", \"reject\")'>Reject</button>";
                    echo "<button class='fired-btn' onclick='handleAction(" . $row["id"] . ", \"fired\")'>Fire</button>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No pending sign-up requests.</td></tr>";
            }

            // Close connection
            $conn->close();
            ?>
        </tbody>
    </table>

    <script>
        function handleAction(id, action) {
            if (confirm(`Are you sure you want to ${action} this request?`)) {
                // Redirect to a PHP script to handle the action
                window.location.href = `action.php?id=${id}&action=${action}`;
            }
        }
    </script>
</body>
</html>