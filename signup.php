<?php
session_start();
include('dbconnect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
    $fullName = trim($_POST['fullName']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $dob = trim($_POST['dob']);
    $mobileNumber = trim($_POST['mobileNumber']);
    $aadharNumber = trim($_POST['aadharNumber']);
    $role = trim($_POST['role']);
    $current_time = date("Y-m-d H:i:s");
    $app = "pending";

    // Handle file upload
    $target_dir = "signup/"; // Create this directory in your server
    $image_name = "";
    
    if(isset($_FILES['profilePhoto']) && $_FILES['profilePhoto']['error'] == 0) {
        $file_extension = pathinfo($_FILES['profilePhoto']['name'], PATHINFO_EXTENSION);
        $image_name = $username . "_" . time() . "." . $file_extension;
        $target_file = $target_dir . $image_name;
        
        // Check file size (limit to 5MB)
        if ($_FILES['profilePhoto']['size'] > 5000000) {
            echo "<script>alert('Sorry, your file is too large.');</script>";
            exit();
        }
        
        // Allow certain file formats
        $allowed_types = array('jpg', 'jpeg', 'png', 'gif');
        if(!in_array(strtolower($file_extension), $allowed_types)) {
            echo "<script>alert('Sorry, only JPG, JPEG, PNG & GIF files are allowed.');</script>";
            exit();
        }
    }

    // Hash the password
    $hashed_password = $password; // You should use password_hash() here in production
    
    // Modified query to include profile photo
    $query = "INSERT INTO signup_table(full_name, username, email, password, dob, mobile_number, aadhar_number, role, approved, registration_time) 
              VALUES ('$fullName', '$username', '$email', '$hashed_password', '$dob', '$mobileNumber', '$aadharNumber', '$role', '$app', '$current_time')";
    
    $query2 = "INSERT INTO user_photo(username,photo) 
              VALUES ('$username','$image_name')";
    
    $result = mysqli_query($con, $query);
    $result = mysqli_query($con, $query2);

    if ($result) 
    {
        // Move uploaded file if it exists
        if(!empty($image_name)) {
            if (!move_uploaded_file($_FILES['profilePhoto']['tmp_name'], $target_file)) {
                echo "<script>alert('Sorry, there was an error uploading your file.');</script>";
            }
        }
        
        $approved = "SELECT * FROM signup_table WHERE username='$username'";
        $r = mysqli_query($con, $approved);
        if ($r && $user = mysqli_fetch_assoc($r)) 
        { 
            $_SESSION['role'] = $role;
            $_SESSION['pass'] = $hashed_password;
            header("Location: approved.php");
            exit();
        } else {
            echo "<script>alert('Error: Unable to fetch user approval status: " . mysqli_error($con) . "');</script>";
        }
    } else {
        echo "<script>alert('Error during registration: " . mysqli_error($con) . "');</script>";
    }
}

mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>GSECL Sign Up</title>
  <link rel="stylesheet" href="signup.css">
</head>

<body>
  <main class="container">
    <div class="signup-container">
      <!-- Left Section -->
      <div class="left-section">
        <div class="left-content">
          <h1 class="company-title">GSECL Sign up</h1>
          <h2 class="main-title">Get Started with Us</h2>
          <p class="subtitle">Complete these easy steps to register your account.</p>
          <div class="steps-container">
            <div class="step">
              <span class="step-number">1</span>
              <span class="step-text">Sign up your account</span>
            </div>
            <div class="step">
              <span class="step-number">2</span>
              <span class="step-text">Set up your workspace</span>
            </div>
            <div class="step">
              <span class="step-number">3</span>
              <span class="step-text">Set up your profile</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Right Section -->
      <div class="right-section">
        <h2 class="form-title">Sign Up Account</h2>
        <p class="form-subtitle">Enter your personal data to create your account.</p>

        <form id="signup-form" class="form" action="" method="POST" enctype="multipart/form-data">
          <!-- Add this right after the Role dropdown in your form -->
          <div class="form-group photo-upload-container">
            <label for="photo-upload">Profile Photo</label>
            <div class="photo-preview hidden" id="photo-preview">
              <img src="" alt="Profile preview" id="preview-image">
            </div>
            <div class="upload-button-container">
              <label class="upload-button" for="photo-upload">
                <span>Upload Photo</span>
              </label>
              <input type="file" id="photo-upload" name="profilePhoto" accept="image/*">
            </div>
            <div class="error-message" id="photo-error"></div>
          </div>
          <!-- Full Name -->
          <div class="form-group">
            <label for="full-name">Full Name</label>
            <input type="text" id="full-name" name="fullName" placeholder="Enter your full name" required>
            <div class="error-message" id="full-name-error"></div>
          </div>

          <!-- Username -->
          <div class="form-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" placeholder="Choose a username" required>
            <div class="error-message" id="username-error"></div>
          </div>

          <!-- Email -->
          <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="Enter your email address" required>
            <div class="error-message" id="email-error"></div>
          </div>

          <!-- Password -->
          <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Create a password" required>
            <div class="description">Must be at least 8 characters.</div>
            <div class="error-message" id="password-error"></div>
          </div>

          <!-- Date of Birth -->
          <div class="form-group">
            <label for="dob">Date of Birth</label>
            <input type="date" id="dob" name="dob" required>
            <div class="error-message" id="dob-error"></div>
          </div>

          <!-- Mobile Number -->
          <div class="form-group">
            <label for="mobile-number">Mobile Number</label>
            <input type="tel" id="mobile-number" name="mobileNumber" placeholder="Enter your mobile number" pattern="[6-9]{1}[0-9]{9}" maxlength="10" required>
            <div class="error-message" id="mobile-number-error"></div>
          </div>

          <!-- Aadhar Card Number -->
          <div class="form-group">
            <label for="aadhar-number">Aadhar Card Number</label>
            <input type="text" id="aadhar-number" name="aadharNumber" placeholder="Enter your Aadhar card number" pattern="[0-9]{12}" maxlength="12" required>
            <div class="error-message" id="aadhar-number-error"></div>
          </div>

          <!-- Role -->
          <label for="role">Roles</label>
          <select name="role" required>
            <option value="" disabled selected>Select Your Role</option>

            <option value="Shift Engineer">Shift Engineer</option>
            <option value="Plant Supervisor">Plant Supervisor</option>
          </select>

          <!-- Terms and Conditions -->
          <div class="form-group checkbox-group">
            <div class="checkbox-container">
              <input type="checkbox" id="agree" name="agree" required>
              <label for="agree">
                I agree to the <a href="#" class="link">User Agreement & Privacy Policy</a>
              </label>
            </div>
            <div class="error-message" id="agree-error"></div>
          </div>

          <!-- Submit Button -->
          <button type="submit" class="submit-button">Sign Up</button>

          <pre style="font-size: 1.2rem;
  color: white; padding-left:130px;">Already Signup?  <a href="login.php">Login</a></pre>
        </form>
      </div>
    </div>
  </main>

  <script src="signup.js"></script>
</body>

</html>