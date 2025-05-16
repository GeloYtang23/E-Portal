<?php
session_start();
require 'db.php';

$userId = $_SESSION['user_id'] ?? null;

if (!$userId) {
    header("Location: index.php");
    exit;
}

// Fetch updated user info from DB
$stmt = $conn->prepare("SELECT full_name, profile_image FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$fullName = $user['full_name'];
$profileImage = $user['profile_image'] ?? 'default.png';
$profileImagePath = 'img/' . $profileImage;

// ✅ Update session with latest user data
$_SESSION['full_name'] = $fullName;
$_SESSION['profile_image'] = $profileImage;




  // Fetch total population, male, and female counts
$totalPopulationQuery = "SELECT COUNT(*) AS total_population FROM users";
$maleCountQuery = "SELECT COUNT(*) AS male_count FROM users WHERE gender = 'male'";
$femaleCountQuery = "SELECT COUNT(*) AS female_count FROM users WHERE gender = 'female'";

$totalPopulationResult = $conn->query($totalPopulationQuery);
$maleCountResult = $conn->query($maleCountQuery);
$femaleCountResult = $conn->query($femaleCountQuery);

if ($totalPopulationResult && $maleCountResult && $femaleCountResult) {
    $totalPopulation = $totalPopulationResult->fetch_assoc()['total_population'] ?? 0;
    $maleCount = $maleCountResult->fetch_assoc()['male_count'] ?? 0;
    $femaleCount = $femaleCountResult->fetch_assoc()['female_count'] ?? 0;

    // Store the counts in the session for use in admin_dashboard.php
    $_SESSION['total_population'] = $totalPopulation;
    $_SESSION['male_count'] = $maleCount;
    $_SESSION['female_count'] = $femaleCount;
} else {
    error_log("Error in queries: " . $conn->error);
}

?>


<!DOCTYPE html>
<html lang="en">
    <head>
      <meta charset="UTF-8">
      <title>E-Barangay | Admin Dashboard</title>
      <link rel="stylesheet" href="css/admin_dashboard.css">
      <link rel="icon" href="img/BAKHAWAN-removebg-preview.png" type="image/x-icon" />
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
      <link rel="icon" href="img/BAKHAWAN-removebg-preview.png" type="image/x-icon" />
      <script src="https://kit.fontawesome.com/7b562488b7.js" crossorigin="anonymous"></script>
    </head>
<body>

<!-- Sidebar -->
<?php include 'sidebar.php'; ?>

  <!-- Main Content -->
  <div class="main-content">
    <div class="topbar">
    <div class="searchbar" style="visibility: hidden; height: 0;">
  <!-- Search bar visually hidden on dashboard but layout stays intact -->
  <form method="GET">
    <input type="text" name="search" placeholder="Search users..." />
  </form>
</div>



<div class="profile">
<?php
  $profileImage = $_SESSION['profile_image'] ?? 'PIC1.jpg';
  $fullName = $_SESSION['full_name'] ?? 'Admin';
?>
  <img src="img/<?= htmlspecialchars($profileImage) ?>" alt="Admin" class="profile-pic" onclick="toggleDropdown()" />
  <span class="profile-name">Welcome, <?= htmlspecialchars($fullName) ?></span> <i class="fa-solid fa-caret-down"></i>

          <div class="dropdown" id="dropdown">

                <a href="#" onclick="openProfileModal()">Settings</a>
                <a href="logout.php">Logout</a>

      </div>

      <!-- Profile Modal -->
<div class="modal-overlay" id="profileModal">
  <div class="modal-box">
    <h3>Profile Settings</h3>
    <form action="upload_profile.php" method="POST" enctype="multipart/form-data">
  <label for="profile_image">Upload New Photo</label>
  <input type="file" name="profile_image" accept="image/*" required>

  <label for="full_name">Full Name</label>
  <input type="text" name="full_name" value="<?= htmlspecialchars($_SESSION['full_name'] ?? '') ?>" required>

  <button type="submit" name="save_profile">Save Changes</button>
</form>

    <button onclick="closeProfileModal()" style="margin-top: 10px; background-color: #ccc; color: black;">Cancel</button>
  </div>
</div>


</div>
</div>


        <div class="content-area">
            <h2>Dashboard</h2>

      <div class="dashboard-cards">
    <div class="card">
        <div class="icon"><i class="fas fa-users"></i></div>
        <h3>POPULATION</h3>
        <p id="population"><?= $_SESSION['total_population'] ?? 0 ?></p>
        <span>Total Population</span>
    </div>

    <div class="card">
        <div class="icon"><i class="fas fa-male"></i></div>
        <h3>MALE</h3>
        <p id="male"><?= $_SESSION['male_count'] ?? 0 ?></p>
        <span>Total Male</span>
    </div>

    <div class="card">
        <div class="icon"><i class="fas fa-female"></i></div>
        <h3>FEMALE</h3>
        <p id="female"><?= $_SESSION['female_count'] ?? 0 ?></p>
        <span>Total Female</span>
    </div>

<!--
      <div class="card">
        <div class="icon"><i class="fas fa-fingerprint"></i></div>
        <h3>VOTERS</h3>
        <p id="voters">0</p>
        <span>Total Voters</span>
      </div>

      <div class="card">
        <div class="icon"><i class="fas fa-exclamation-triangle"></i></div>
        <h3>NON VOTERS</h3>
        <p id="non_voters">0</p>
        <span>Total Non Voters</span>
      </div>

      <div class="card">
        <div class="icon"><i class="fas fa-gavel"></i></div>
        <h3>BLOTTER</h3>
        <p id="blotter">0</p>
        <span>Blotter Information</span>
      </div>

      <div class="card">
        <div class="icon"><i class="fas fa-signs-post"></i></div>
        <h3>PUROK</h3>
        <p id="purok">0</p>
        <span>Total Purok</span>
      </div>


      <div class="card">
        <div class="icon">Tbd</div>
        <h3>Pending Documents</h3>
        <p id="revenue">0</p>
        <span>Total Pending Documents</span>
      </div>

         <div class="card">
        <div class="icon">Tbd</div>
        <h3>Verified Account</h3>
        <p id="revenue">0</p>
        <span>Total Verified Account</span>
      </div>

         <div class="card">
        <div class="icon">Tbd</div>
        <h3>Non Verified Account</h3>
        <p id="revenue">0</p>
        <span>Total Non Verified Account</span>
      </div> -->

    </div>

</div>

 

</body>

<script>

    function toggleDropdown() {
      const dropdown = document.getElementById("dropdown");
      dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";
    }

    // Optional: Close dropdown on outside click
    window.onclick = function(event) {
      if (!event.target.matches('.profile-pic')) {
        const dropdown = document.getElementById("dropdown");
        if (dropdown && dropdown.style.display === "block") {
          dropdown.style.display = "none";
        }
      }
    };



// Profile settings modal functionality dropdown
    function toggleDropdown() {
    const dropdown = document.getElementById('dropdown');
    dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
  }

  function openProfileModal() {
    document.getElementById('profileModal').style.display = 'flex';
    document.getElementById('dropdown').style.display = 'none';
  }

  function closeProfileModal() {
    document.getElementById('profileModal').style.display = 'none';
  }

  // Optional: close on outside click
  window.addEventListener('click', function(e) {
    const modal = document.getElementById('profileModal');
    if (e.target === modal) {
      closeProfileModal();
    }
  });

</script>

</html>



