<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="container">
        <!-- Left Section -->
        <div class="left-section">
            <h2>Welcome Back</h2>
            <p>Login to continue to your account.</p>
            <div class="steps">
                <div class="step"><span>1</span> Enter Username</div>
                <div class="step"><span>2</span> Enter Your Password</div>
            </div>
        </div>
        <!-- Right Section -->
        <div class="right-section">
            <h2>Login</h2>
            <p class="subtitle">Enter your credentials to access your account.</p>
            <!-- Login Form -->
            <form id="login-form" action="" method="POST">
                <select name="role" required>
                    <option value="" disabled selected>Select Your Role</option>
                    <option value="Admin">Admin</option>
                    <option value="Shift Engineer">Shift Engineer</option>
                    <option value="Plant Supervisor">Plant Supervisor</option>
                </select>
                <input type="text" name="user" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                    
                <button type="submit" class="login-btn">Login</button>
                <br>
                <br>
                <pre style="font-size: 1.2rem;
  color: white; padding-left:130px;">Not Login?  <a href="signup.php">Signup</a></pre>
            </form>
        </div>
    </div>
    <?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "log_book";

// Establish the connection to the database
$con = mysqli_connect($servername, $username, $password, $database);

// Check if the connection was successful
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// Start a session
session_start();

// Process form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['user']);
    $password = trim($_POST['password']);
    $role = trim($_POST['role']);

    if (empty($name) || empty($password) || empty($role)) {
        echo "<h3 style='color: red; text-align: center;'>Please fill in all fields.</h3>";
    } else {
        // Use a prepared statement to prevent SQL Injection
        $query = "SELECT * FROM login_table WHERE username = ? AND role = ?";
        $stmt = mysqli_prepare($con, $query);
        if ($stmt === false) {
            echo "<h3 style='color: red; text-align: center;'>Error preparing statement: " . mysqli_error($con) . "</h3>";
        } else {
            mysqli_stmt_bind_param($stmt, "ss", $name, $role);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if ($row = mysqli_fetch_assoc($result)) {
                // Compare the plain text password directly (temporary solution)
                if ($password === $row['password']) {
                    // Password is correct, set session variables and redirect based on role
                    $_SESSION['username'] = $row['username'];
                    $_SESSION['role'] = $row['role'];

                    if ($row['role'] == "Admin") {
                        header("Location: logbook.php");
                    } elseif ($row['role'] == "Shift Engineer") {
                        header("Location: report-new-issue.php");
                    } elseif ($row['role'] == "Plant Supervisor") {
                        header("Location: reportissue.php");
                    }
                    exit();
                } else {
                    // Password is incorrect
                    echo "<script>alert('Invalid password.');</script>";
                }
            } else {
                // User with the given username and role does not exist
                echo "<script>alert('Invalid username or role.');</script>";
            }
            mysqli_stmt_close($stmt);
        }
    }
}

// Close the database connection
mysqli_close($con);
?>
</body>
</html>