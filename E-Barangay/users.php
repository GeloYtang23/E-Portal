<?php
session_start();
require 'db.php';

            // Admin authentication
            if (!isset($_SESSION['logged_in']) || $_SESSION['role'] !== 'admin') {
                header("Location: index.php");
                exit;
            }

            // Fetch admin user details
          $userId = $_SESSION['user_id'] ?? null;

          if ($userId) {
              $stmt = $conn->prepare("SELECT full_name, profile_image FROM users WHERE id = ?");
              $stmt->bind_param("i", $userId);
              $stmt->execute();
              $result = $stmt->get_result();
              $adminUser = $result->fetch_assoc();

              $adminFullName = $adminUser['full_name'] ?? 'Admin';
              $adminProfileImage = $adminUser['profile_image'] ?? 'default.png';
              $adminProfilePath = 'img/' . $adminProfileImage;
          } else {
              $adminFullName = 'Admin';
              $adminProfilePath = 'img/default.png';
          }



             // Handle add user
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_user'])) {
    $full_name = $_POST['full_name'];
    $birthdate = $_POST['birthdate'];
    $gender = $_POST['gender'];
    $age = $_POST['age'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Handle profile image
    $imageName = uniqid() . '_' . basename($_FILES['profile_image']['name']);
    $imageTmpName = $_FILES['profile_image']['tmp_name'];
    $imagePath = 'uploads/' . $imageName;

    if (move_uploaded_file($imageTmpName, $imagePath)) {
        // Insert user
        $stmt = $conn->prepare("INSERT INTO users (full_name, birthdate, gender, age, email, role, password, profile_image, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("sssissss", $full_name, $birthdate, $gender, $age, $email, $role, $password, $imagePath);
        $stmt->execute();
    } else {
        echo "Failed to upload image.";
    }
}



              // Handle delete user
              if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_user'])) {
                $id = $_POST['delete_id'];
                $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
                $stmt->bind_param("i", $id);
                $stmt->execute();
                header('Location: users.php');
                exit;
              }


          // Handle update user
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_user'])) {
    $id = $_POST['id'];
    $full_name = $_POST['full_name'];
    $birthdate = $_POST['birthdate'];
    $gender = $_POST['gender'];
    $age = $_POST['age'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    // Check if a new profile image is uploaded
    if (!empty($_FILES['profile_image']['name'])) {
        $imageName = uniqid() . '_' . basename($_FILES['profile_image']['name']);
        $imageTmpName = $_FILES['profile_image']['tmp_name'];
        $imagePath = 'uploads/' . $imageName;

        // Upload the new image
        if (move_uploaded_file($imageTmpName, $imagePath)) {
            // Update user with the new image
            $stmt = $conn->prepare("UPDATE users SET full_name=?, birthdate=?, gender=?, age=?, email=?, role=?, profile_image=? WHERE id=?");
            $stmt->bind_param("sssisssi", $full_name, $birthdate, $gender, $age, $email, $role, $imagePath, $id);
        } else {
            echo "Failed to upload the new image.";
        }
    } else {
        // Update user without changing the image
        $stmt = $conn->prepare("UPDATE users SET full_name=?, birthdate=?, gender=?, age=?, email=?, role=? WHERE id=?");
        $stmt->bind_param("sssissi", $full_name, $birthdate, $gender, $age, $email, $role, $id);
    }


                // Execute the update
                $stmt->execute();
                header('Location: users.php');
                exit;
}


                // Handle search
                $search = $_GET['search'] ?? '';
                if ($search) {
                    $search_param = "%{$search}%";
                    $stmt = $conn->prepare("SELECT * FROM users WHERE full_name LIKE ? OR email LIKE ?");
                    $stmt->bind_param("ss", $search_param, $search_param);
                } else {
                    $stmt = $conn->prepare("SELECT * FROM users");
                }
                $stmt->execute();
                $result = $stmt->get_result();


?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>E-Barangay | User Management</title>
  <link rel="stylesheet" href="css/admin_dashboard.css">
  <link rel="icon" href="img/BAKHAWAN-removebg-preview.png" type="image/x-icon" />
  <script src="https://kit.fontawesome.com/7b562488b7.js" crossorigin="anonymous"></script>

  <style>
    .action-buttons button {
      margin-right: 5px;
      padding: 4px 8px;
    }
  </style>
</head>
<body>

<?php include 'sidebar.php'; ?>

<div class="main-content">
  <div class="topbar">
    <div class="searchbar">
      <form method="GET">
        <input type="text" name="search" placeholder="Search users..." value="<?= htmlspecialchars($search) ?>" />
      </form>
    </div>
    <div class="profile">
     <img src="<?= htmlspecialchars($adminProfilePath) ?>" class="profile-pic" onclick="toggleDropdown()" style="border-radius: 50%;" />
<span class="profile-name">Welcome, <?= htmlspecialchars($adminFullName) ?>&nbsp; &nbsp;<i class="fa-solid fa-caret-down"></i></span>

      <div class="dropdown" id="dropdown">
        <a href="#">Profile</a>
        <a href="logout.php">Logout</a>
      </div>
    </div>
  </div>

  <div class="content-area">
    <h2>User Management</h2>
    <button class="add-user-btn" onclick="document.getElementById('addModal').style.display='block'">Add User</button>
    <p>Total users: <?= $result->num_rows ?></p>

    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Full Name</th>
          <th>Birthdate</th>
          <th>Gender</th>
          <th>Age</th>
          <th>Email</th>
          <th>Profile</th>
          <th>Role</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
          <tr>
  <td><?= $row['id'] ?></td>
  <td><?= $row['full_name'] ?></td>
  <td><?= $row['birthdate'] ?></td>
  <td><?= $row['gender'] ?></td>
  <td><?= $row['age'] ?></td>
  <td><?= $row['email'] ?></td>
        <td>
       <?php
    $profileImage = $row['profile_image'];
    if (strpos($profileImage, 'uploads/') === 0) {
        $imgSrc = htmlspecialchars($profileImage);
    } else {
        $imgSrc = 'img/' . htmlspecialchars($profileImage);
    }
  ?>
  <img src="<?= $imgSrc ?>" alt="Profile" width="50" height="50" style="border-radius: 50%;">
        </td>
  <td><?= $row['created_at'] ?></td>
  <td>
    <button onclick='openEditModal(<?= json_encode($row) ?>)' class="btn-edit">Edit</button>
    <form method="POST" action="" style="display:inline;">
      <input type="hidden" name="delete_id" value="<?= $row['id'] ?>">
      <button type="submit" name="delete_user" class="btn-delete" onclick="return confirm('Delete this user?')">Delete</button>
    </form>
  </td>
</tr>

        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Add User Modal -->
<div id="addModal" class="modal-overlay">
 <form class="modal-box" method="POST" enctype="multipart/form-data">

    <h3>Add User</h3>
    <input name="full_name" placeholder="Full Name" required>
    <input name="birthdate" type="date" id="add-birthdate" onchange="calculateAge('add-birthdate', 'age')" required>
    <input type="number" name="age" id="age" placeholder="Age" class="modal-input" readonly required>

<select name="gender" required>
  <option value="" disabled selected>Select Gender</option>
  <option value="Male">Male</option>
  <option value="Female">Female</option>
  <option value="Other">Other</option>
</select>

   
    <input name="email" placeholder="Email" type="email" required>
    <input name="password" type="password" placeholder="Password" class="modal-input" id="password" required>
    <input type="file" name="profile_image" accept="image/*" required class="modal-input" />
    <div class="strength-bar" style="display: none;">
          <div id="strengthLine" class="strength-line"></div>
    </div>
<small id="strengthMessage" style="font-weight: bold; display: none;"></small>

    <select name="role">
      <option value="user">User</option>
      <option value="admin">Admin</option>
    </select>
    <button name="add_user" type="submit">Add</button>
    <button type="button" onclick="closeModal('addModal')">Cancel</button>
  </form>
</div>



<!-- Edit User Modal -->
<div id="editModal" class="modal-overlay">
  <form class="modal-box" method="POST" enctype="multipart/form-data">
    <h3>Edit User</h3>
    <input type="hidden" name="id" id="edit-id">
    <input name="full_name" id="edit-full_name" required>
    <input name="birthdate" type="date" id="edit-birthdate" onchange="calculateAge('edit-birthdate', 'edit-age')" required>   
    <input type="number" name="age" id="edit-age" placeholder="Age" readonly required>

    <select name="gender" id="edit-gender" required>
      <option value="" disabled selected>Select Gender</option>
      <option value="male">Male</option>
      <option value="female">Female</option>
      <option value="other">Other</option>
    </select>
    <input name="email" type="email" id="edit-email" required>
   <select name="role" id="edit-role">
      <option value="user">User</option>
      <option value="admin">Admin</option>
    </select>
    <input type="file" name="profile_image" accept="image/*">
    <button name="update_user" type="submit">Update</button>
    <button type="button" onclick="closeModal('editModal')">Cancel</button>
  </form>
</div>


<script>
function toggleDropdown() {
  const dropdown = document.getElementById("dropdown");
  dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";
}

function openEditModal(user) {
  document.getElementById('edit-id').value = user.id;
  document.getElementById('edit-full_name').value = user.full_name;
  document.getElementById('edit-birthdate').value = user.birthdate;
  document.getElementById('edit-gender').value = user.gender;
  document.getElementById('edit-age').value = user.age;
  document.getElementById('edit-email').value = user.email;
  document.getElementById('edit-role').value = user.role;

  // Display the modal
  document.getElementById('editModal').style.display = 'block';
}

window.onclick = function(event) {
  if (!event.target.matches('.profile-pic')) {
    const dropdown = document.getElementById("dropdown");
    if (dropdown && dropdown.style.display === "block") {
      dropdown.style.display = "none";
    }
  }
};




function toggleDropdown() {
  const dropdown = document.getElementById("dropdown");
  dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";
}

function openEditModal(user) {
  document.getElementById('edit-id').value = user.id;
  document.getElementById('edit-full_name').value = user.full_name;
  document.getElementById('edit-birthdate').value = user.birthdate;
  document.getElementById('edit-gender').value = user.gender;
  document.getElementById('edit-age').value = user.age;
  document.getElementById('edit-email').value = user.email;
  document.getElementById('edit-role').value = user.role;
  document.getElementById('editModal').style.display = 'flex';


}

function closeModal(id) {
  document.getElementById(id).style.display = 'none';
}

document.querySelector("button[onclick*='addModal']").addEventListener("click", () => {
  document.getElementById("addModal").style.display = "flex";
});

window.onclick = function(event) {
  if (event.target.classList.contains('modal-overlay')) {
    event.target.style.display = 'none';
  }
}


               function calculateAge(birthdateId, ageId) {
                const birthdate = document.getElementById(birthdateId).value;
                const ageInput = document.getElementById(ageId);

                if (birthdate) {
                  const birthDateObj = new Date(birthdate);
                  const today = new Date();
                  let age = today.getFullYear() - birthDateObj.getFullYear();
                  const monthDiff = today.getMonth() - birthDateObj.getMonth();

                  // Adjust age if the birthdate hasn't occurred yet this year
                  if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDateObj.getDate())) {
                    age--;
                  }

                  ageInput.value = age; // Set the calculated age
                } else {
                  ageInput.value = ''; // Clear the age input if no birthdate is selected
                }
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
