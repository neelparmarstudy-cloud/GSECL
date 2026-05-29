<?php
session_start();
include('dbconnect.php');

// Process form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['user'];

    // Check if username is empty
    if (empty($username)) {
        echo "<script>alert('Please enter a username.');</script>";
    } else {
        // Check approval status in signup_table
        $query = "SELECT approved FROM signup_table WHERE username='$username'";
        $result = mysqli_query($con, $query);

        // Check if the query worked
        if ($result) {
            // Check if the username exists
            if (mysqli_num_rows($result) > 0) {
                $user = mysqli_fetch_assoc($result);
                $approved = $user['approved'];

                // Check the approved status
                if ($approved == 'approved') {
                    echo "<script>alert('Approved by admin.'); window.location.href='login.php';</script>";
                    // $insert_login = "INSERT INTO login_table 
                    //                 VALUES ('$username', '$password', '$approved','$role')";
                    // $insert_result = mysqli_query($con, $insert_login);
                } else {
                    echo "<script>alert('Not approved by admin.');</script>";
                }
            } else {
                echo "<script>alert('Username not found. Please sign up first.'); window.location.href='signup.php';</script>";
            }
        } else {
            echo "<script>alert('Error checking approval status.');</script>";
        }
    }
}

mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check Approval</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }
        body {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-image: url(https://img.freepik.com/free-vector/gradient-black-background-with-cubes_23-2149177091.jpg);
            background-size: cover;
            background-repeat: no-repeat;
            color: white;
        }
        .message {
            position: absolute;
            top: 20px;
            text-align: center;
            width: 100%;
            font-size: 24px;
            font-weight: bold;
            color: #fff;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
        }
        .container {
            display: flex;
            width: 80%;
            max-width: 900px;
            background-color: #121212;
            border-radius: 20px;
            overflow: hidden;
            margin-top: 60px;
        }
        .left-section {
            width: 50%;
            background: linear-gradient(to bottom, purple, black);
            padding: 40px;
            text-align: center;
        }
        .left-section h2 {
            font-size: 28px;
            margin-bottom: 10px;
        }
        .left-section p {
            font-size: 16px;
            margin-bottom: 20px;
        }
        .steps {
            text-align: left;
        }
        .step {
            display: flex;
            align-items: center;
            background: rgba(255, 255, 255, 0.1);
            margin: 10px 0;
            padding: 10px;
            border-radius: 10px;
        }
        .step span {
            display: inline-block;
            width: 30px;
            height: 30px;
            background: white;
            color: black;
            font-weight: bold;
            text-align: center;
            line-height: 30px;
            border-radius: 50%;
            margin-right: 10px;
        }
        .right-section {
            width: 50%;
            padding: 40px;
            background: black;
        }
        .subtitle {
            color: gray;
            margin-bottom: 20px;
        }
        form input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            background: #333;
            border: none;
            color: white;
            font-size: 16px;
            border-radius: 5px;
            outline: none;
        }
        form input:focus {
            outline: 2px solid purple;
            background: #444;
        }
        .login-btn {
            width: 100%;
            padding: 12px;
            background: white;
            color: black;
            border: none;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            border-radius: 5px;
        }
        .login-btn:hover {
            background: purple;
            color: white;
        }
        @media (max-width: 768px) {
            .container {
                flex-direction: column;
                width: 90%;
            }
            .left-section, .right-section {
                width: 100%;
            }
            .message {
                font-size: 18px;
                top: 10px;
            }
        }
    </style>
</head>
<body>
    <h3 class="message">Signup successful! Please check if your account is approved.</h3>
    <div class="container">
        <!-- Left Section -->
        <div class="left-section">
            <h2>Welcome Back</h2>
            <p>Check if your account has been approved.</p>
            <div class="steps">
                <div class="step"><span>1</span> Enter Username</div>
                <div class="step"><span>2</span> Check Approval Status</div>
            </div>
        </div>
       
        <!-- Right Section -->
        <div class="right-section">
            <h2>Check Approval</h2>
            <p class="subtitle">Enter your username to check approval status.</p>
            
            <form action="approved.php" method="POST">
                <input type="text" name="user" placeholder="Username" required>
                <button type="submit" class="login-btn">Check Approval</button>
                <br>
                <br>
                <pre style="font-size: 1.2rem;
  color: white; padding-left:100px;">Already Signup?  <a href="login.php">Login</a></pre>
            </form>
        </div>
    </div>
</body>
</html> 