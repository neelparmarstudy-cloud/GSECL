<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>GSECL Sign Up</title>
  <link rel="stylesheet" href="s.css">
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

        <form id="signup-form" class="form">
          <!-- User Photo -->
          <div class="form-group">
            <label for="photo-upload">User Photo</label>
            <div class="photo-upload-container">
              <div id="photo-preview" class="photo-preview hidden">
                <img id="preview-image" src="#" alt="Preview">
              </div>
              <div class="upload-button-container">
                <label for="photo-upload" class="upload-button">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="upload-icon">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                    <polyline points="17 8 12 3 7 8"></polyline>
                    <line x1="12" y1="3" x2="12" y2="15"></line>
                  </svg>
                  <span>Upload Photo</span>
                </label>
                <input id="photo-upload" type="file" accept="image/*" required>
              </div>
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
            <input type="tel" id="mobile-number" name="mobileNumber" placeholder="Enter your mobile number" required>
            <div class="error-message" id="mobile-number-error"></div>
          </div>

          <!-- Aadhar Card Number -->
          <div class="form-group">
            <label for="aadhar-number">Aadhar Card Number</label>
            <input type="text" id="aadhar-number" name="aadharNumber" placeholder="Enter your Aadhar card number" required>
            <div class="error-message" id="aadhar-number-error"></div>
          </div>

          <!-- Age -->
          <div class="form-group">
            <label for="age">Age</label>
            <input type="text" id="age" name="age" placeholder="Enter your age" required>
            <div class="error-message" id="age-error"></div>
          </div>

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
        </form>
      </div>
    </div>
  </main>

  <script src="s.js"></script>
</body>
</html>