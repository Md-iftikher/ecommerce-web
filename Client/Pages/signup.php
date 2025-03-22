<?php 
if(isset($_GET['account_exists'])) {
  echo '<script>window.onload = function() { alert("Account already exists!"); }</script>';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Sign Up Page</title>
  <link rel="stylesheet" href="../Styles/signup.css">
</head>
<body>
  <div class="signup-container">
    <h2>Sign Up</h2>
    <form id="signup-form" action="../php/login/insert_customer.php" method="POST">
      <!-- First Name and Last Name side by side -->
      <div class="input-group double-input">
        <div class="input-half">
          <label for="first-name">First Name</label>
          <input type="text" id="first-name" name="first-name" placeholder="Enter your first name" required />
        </div>
        <div class="input-half">
          <label for="last-name">Last Name</label>
          <input type="text" id="last-name" name="last-name" placeholder="Enter your last name" />
        </div>
      </div>

      <!-- Email and Contact Number side by side -->
      <div class="input-group double-input">
        <div class="input-half">
          <label for="email">Email</label>
          <input type="email" id="email" name="email" placeholder="Enter your email" required />
        </div>
        <div class="input-half">
          <label for="contact">Contact Number</label>
          <input type="tel" id="contact" name="contact" placeholder="Enter your contact number" required />
        </div>
      </div>

      <!-- Date of Birth -->
      <div class="input-group double-input">
      <div class="input-half">
        <label for="dob">Date of Birth</label>
        <input type="date" id="dob" name="dob" required />
      </div>

      <!-- Gender -->
      <div class="input-half gender-group">
        <label for="gender">Gender</label>
        <div>
          <label><input type="radio" name="gender" value="M" required /> Male</label>
          <label><input type="radio" name="gender" value="F" /> Female</label>
          <label><input type="radio" name="gender" value="O" /> Other</label>
        </div>
      </div>
      </div>

      <!-- Password and Confirm Password -->
      <div class="input-group double-input">
        <div class="input-half">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" placeholder="Enter your password" required />
        </div>
        <div class="input-half">
          <label for="confirm-password">Confirm Password</label>
          <input type="password" id="confirm-password" name="confirm-password" placeholder="Confirm your password" required />
        </div>
      </div>

      <button type="submit" class="signup-button">Sign Up</button>
    </form>
    <div class="login-link">
      Already have an account? <a href="#">Login</a>
    </div>
  </div>
  <script src="../js/signup.js"></script>
</body>
</html>
