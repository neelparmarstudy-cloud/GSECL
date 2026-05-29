document.addEventListener("DOMContentLoaded", () => {
  // Photo upload preview
  const photoUpload = document.getElementById("photo-upload");
  const photoPreview = document.getElementById("photo-preview");
  const previewImage = document.getElementById("preview-image");

  photoUpload.addEventListener("change", (event) => {
    const file = event.target.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onloadend = () => {
        previewImage.src = reader.result;
        photoPreview.classList.remove("hidden");
      };
      reader.readAsDataURL(file);
    }
  });

  // Form validation
  const form = document.getElementById("signup-form");

  form.addEventListener("submit", (event) => {
    event.preventDefault();

    let isValid = true;

    // Reset error messages
    const errorMessages = document.querySelectorAll(".error-message");
    errorMessages.forEach((el) => (el.textContent = ""));

    // Validate photo
    if (!photoUpload.files.length) {
      document.getElementById("photo-error").textContent = "User photo is required";
      isValid = false;
    }

    // Validate full name
    const fullName = document.getElementById("full-name").value;
    if (fullName.length < 2) {
      document.getElementById("full-name-error").textContent = "Full name must be at least 2 characters.";
      isValid = false;
    }

    // Validate username
    const username = document.getElementById("username").value;
    if (username.length < 3) {
      document.getElementById("username-error").textContent = "Username must be at least 3 characters.";
      isValid = false;
    }

    // Validate email
    const email = document.getElementById("email").value;
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
      document.getElementById("email-error").textContent = "Please enter a valid email address.";
      isValid = false;
    }

    // Validate password
    const password = document.getElementById("password").value;
    if (password.length < 8) {
      document.getElementById("password-error").textContent = "Password must be at least 8 characters.";
      isValid = false;
    }

    // Validate date of birth
    const dob = document.getElementById("dob").value;
    if (!dob) {
      document.getElementById("dob-error").textContent = "Date of birth is required.";
      isValid = false;
    }

    // Validate mobile number
    const mobileNumber = document.getElementById("mobile-number").value;
    if (mobileNumber.length < 10) {
      document.getElementById("mobile-number-error").textContent = "Please enter a valid mobile number.";
      isValid = false;
    }

    // Validate aadhar number
    const aadharNumber = document.getElementById("aadhar-number").value;
    if (aadharNumber.length < 12) {
      document.getElementById("aadhar-number-error").textContent = "Please enter a valid Aadhar card number.";
      isValid = false;
    }

    // Validate agreement
    const agree = document.getElementById("agree").checked;
    if (!agree) {
      document.getElementById("agree-error").textContent = "You must agree to the terms and conditions";
      isValid = false;
    }

    // Submit form if valid
    if (isValid) {
      form.submit();
    }
  });
});