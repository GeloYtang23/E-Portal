<!-- sidebar.php -->
<div class="sidebar">
  <h2 style="display: flex; align-items: center; gap: 10px;">
    <img src="img/BAKHAWAN-removebg-preview.png" alt="Barangay Logo" style="width: 30px; height: 30px; border-radius: 50%;" />
    E-Barangay Portal
  </h2>
  <a href="admin_dashboard.php" class="<?= basename($_SERVER['PHP_SELF']) === 'admin_dashboard.php' ? 'active' : '' ?>">Dashboard</a>
  <a href="users.php" class="<?= basename($_SERVER['PHP_SELF']) === 'users.php' ? 'active' : '' ?>">User Management</a>
  
</div>
