<?php
      session_start();
      $error_message = $_SESSION['error_message'] ?? '';
      $last_attempt_time = $_SESSION['last_attempt_time'] ?? 0;
      $last_email = $_SESSION['last_email'] ?? '';
      unset($_SESSION['error_message'], $_SESSION['last_email']);

      $login_attempts = $_SESSION['login_attempts'] ?? 0;
      $cooldown_remaining = 0;
      if ($login_attempts >= 3) {
          $time_since_last = time() - $last_attempt_time;
          if ($time_since_last < 60) {
              $cooldown_remaining = 60 - $time_since_last;
          }
      }

      $signup_success = $_SESSION['signup_success'] ?? '';
      $signup_error = $_SESSION['signup_error'] ?? '';
      $show_signup_modal = $_SESSION['show_signup_modal'] ?? false;
      unset($_SESSION['signup_success'], $_SESSION['signup_error'], $_SESSION['show_signup_modal']);

     
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>E-Barangay | Login</title>
  <link rel="stylesheet" href="css/index.css" />
  <link rel="icon" href="img/BAKHAWAN-removebg-preview.png" type="image/x-icon" />
</head>


<body>
  <div class="left">
      <img src="img/BAKHAWAN-removebg-preview.png" alt="Barangay Seal" />
      <h2>E-BARANGAY PORTAL V0.1</h2>
      <p>Bakhawan, Daanbantayan, Cebu<br/>
        Open Hours: Monday–Friday (8AM–5PM)<br/>
        barangaybakhawan@gmail.com / 0912345678
      </p>
  </div>

<div class="right">
    <div class="form-box">
      <h3>BARANGAY RESIDENTS ACCESS</h3>
      <p>E-Barangay Portal: A Barangay Portal System is an online platform designed to streamline and automate the services and transactions within local barangay.</p>
       
<form action="login.php" method="POST">
  
        <input type="email" name="email" placeholder="Email" required value="<?php echo htmlspecialchars($_COOKIE['remembered_email'] ?? $last_email); ?>" />
        <input type="password" name="password" placeholder="Password" required />

     <div class="actions">

              <label class="custom-checkbox">
              <input type="checkbox" name="remember_me" <?php if (isset($_COOKIE['remembered_email'])) echo 'checked'; ?> />
                <span class="checkmark"></span>
                Remember me
              </label>
              <span>Don't have account? <a onclick="openModal()">Sign up</a></span>
      
      </div>

                <!-- Cooldown Message -->
                <?php if ($cooldown_remaining > 0): ?>
                    <p style="color: red;" id="cooldown-message">
                        Please wait <span id="cooldown-timer"><?php echo $cooldown_remaining; ?></span> seconds before trying again.
                    </p>
                <?php endif; ?>

                <!-- Error Message -->
                <?php if (!empty($error_message)): ?>
                    <p style="color: red;" id="error-message"><?php echo htmlspecialchars($error_message); ?></p>
                <?php endif; ?>

          <button type="submit">Login</button>
          
</form>

      </div>

</div>


<!-- Modal -->
<div class="modal-overlay" id="signupModal">
  <div class="modal">
    <span class="close-btn" onclick="closeModal()">×</span>
    <h2>Create Account</h2>


<form action="signup.php" method="POST" enctype="multipart/form-data">
      <input type="text" name="full_name" placeholder="Full Name" class="modal-input" required />
      <input type="date" name="birthdate" id="birthdate" placeholder="Birthdate" class="modal-input" required />
      <input type="number" name="age" id="age" placeholder="Age" class="modal-input" readonly required />


  <select name="gender" id="gender" class="modal-input" required>
        <option value="" disabled selected>Select Gender</option>
        <option value="male">Male</option>
        <option value="female">Female</option>
        <option value="other">Other</option>
  </select>
  
       
        <input type="email" name="email" placeholder="Email" class="modal-input" required />
        <input type="password" name="password" placeholder="Password" class="modal-input" id="password" required />

            <div class="strength-bar" style="display: none;">
              <div id="strengthLine" class="strength-line"></div>
            </div>
            <small id="strengthMessage" style="font-weight: bold; display: none;"></small>

 
  <input type="file" name="profile_image" id="profile_image" class="modal-input" accept="image/*" required>


      <button type="submit">Create Account</button>


      
      <?php if (!empty($signup_success)): ?>
  <p style="color: green; text-align: center;"><?php echo htmlspecialchars($signup_success); ?></p>
<?php elseif (!empty($signup_error)): ?>
  <p style="color: red; text-align: center;"><?php echo htmlspecialchars($signup_error); ?></p>
<?php endif; ?>

</form>

  </div>
</div>

<?php if ($show_signup_modal): ?>
<script>
  document.getElementById('signupModal').style.display = 'flex';
</script>
<?php endif; ?>

  <script>

                      // Calculate age from birthdate
                      document.getElementById('birthdate').addEventListener('change', function () {
                      const birthdate = new Date(this.value);
                      const today = new Date();
                      let age = today.getFullYear() - birthdate.getFullYear();
                      const m = today.getMonth() - birthdate.getMonth();

                      if (m < 0 || (m === 0 && today.getDate() < birthdate.getDate())) {
                          age--;
                      }

                      document.getElementById('age').value = age;
                  });


                  //modal functionality
                  function openModal() {
                    document.getElementById('signupModal').style.display = 'flex';
                  }

                  function closeModal() {
                    document.getElementById('signupModal').style.display = 'none';
                  }

                  // Close modal when clicking outside
                  window.addEventListener('click', function(e) {
                    const modal = document.getElementById('signupModal');
                    if (e.target === modal) {
                      closeModal();
                    }
                  });

                          //Live countdown timer
                          let cooldown = parseInt(document.getElementById('cooldown-timer')?.innerText || "0");
                          if (cooldown > 0) {
                              const timer = document.getElementById('cooldown-timer');
                              const message = document.getElementById('cooldown-message');
                              const error = document.getElementById('error-message');
                              const loginButton = document.querySelector("form button[type='submit']");

                              if (loginButton) loginButton.disabled = true;

                              const interval = setInterval(() => {
                                  cooldown--;
                                  if (cooldown <= 0) {
                                      clearInterval(interval);
                                      message.innerText = "You can now try logging in again.";
                                      if (error) error.remove();
                                      if (loginButton) loginButton.disabled = false;
                                  } else {
                                      timer.innerText = cooldown;
                                  }
                              }, 1000);
                }

                // password strength checker
              document.getElementById('password').addEventListener('input', function () {
                  const password = this.value;
                  const strengthMessage = document.getElementById('strengthMessage');
                  const strengthBar = document.querySelector('.strength-bar');
                  const strengthLine = document.getElementById('strengthLine');

                  if (password.length === 0) {
                      strengthMessage.style.display = 'none';
                      strengthBar.style.display = 'none';
                      return;
                  }

                  strengthMessage.style.display = 'inline';
                  strengthBar.style.display = 'block';

                  let strength = 0;
                  if (password.length >= 6) strength++;
                  if (/[A-Z]/.test(password)) strength++;
                  if (/[0-9]/.test(password)) strength++;
                  if (/[^A-Za-z0-9]/.test(password)) strength++;

                  switch (strength) {
                      case 0:
                      case 1:
                          strengthMessage.textContent = "Weak";
                          strengthMessage.style.color = "red";
                          strengthLine.style.width = "33%";
                          strengthLine.style.backgroundColor = "red";
                          break;
                      case 2:
                      case 3:
                          strengthMessage.textContent = "Good";
                          strengthMessage.style.color = "orange";
                          strengthLine.style.width = "66%";
                          strengthLine.style.backgroundColor = "orange";
                          break;
                      case 4:
                          strengthMessage.textContent = "Strong";
                          strengthMessage.style.color = "green";
                          strengthLine.style.width = "100%";
                          strengthLine.style.backgroundColor = "green";
                          break;
                  }
              });


  </script>
</body>
</html>
