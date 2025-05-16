<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['role'] === 'admin') {
    header("Location: index.php");
    exit;
}

$profileImage = 'uploads/default.png';
if (isset($_SESSION['profile_image']) && !empty($_SESSION['profile_image'])) {
    if (strpos($_SESSION['profile_image'], 'uploads/') === 0) {
        $profileImage = htmlspecialchars($_SESSION['profile_image']);
    } else {
        $profileImage = 'img/' . htmlspecialchars($_SESSION['profile_image']);
    }
}

        //TEMPORARY ALERT
          if (isset($_GET['feedback']) && $_GET['feedback'] === 'success') {
              echo "<script>alert('Feedback submitted successfully!');</script>";
          }

?>




<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>E-Barangay Portal | Userpage</title>
    <link rel="icon" href="img/BAKHAWAN-removebg-preview.png" type="image/x-icon" />

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .hero-logo {
      width: 150px;
    }
  
    .officials-img, .announcement-img {
      width: 100%;
      height: 200px;
      object-fit: cover;
      border-radius: 10px;
    }
  
    .official-card {
      text-align: center;
    }
  
    .footer {
      background-color: #4169E1;
      color: white;
    }
  
    .slider-prev-btn,
    .slider-next-btn {
      z-index: 10;
      font-size: 1.5rem;
      width: 40px;
      height: 40px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
    }
  
    /* Ensure images in Barangay Officials cards fit properly */
    #officials .card-img-top {
      width: 100%;
      height: 300px; /* Set a consistent height */
      object-fit: cover; /* Crop the image to fit the area */
      border-radius: 10px; /* Optional: Add rounded corners */
    }
  
    /* Ensure all cards in Barangay Officials have consistent dimensions */
    #officials .card {
      width: 300px; /* Set a consistent width */
      height: auto; /* Allow height to adjust based on content */
    }
  </style>


  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-primary">
    <div class="container">
      <!-- Logo and Site Name -->
      <a class="navbar-brand d-flex align-items-center text-white" href="#">
        <img src="img/BAKHAWAN-removebg-preview.png" alt="Logo" width="40" height="40" class="me-2">
        <strong>E-Barangay Portal</strong>
      </a>
  
      <!-- Navbar Toggler -->
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
  
      <!-- Navbar Content -->
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">

                <!-- Navigation Links -->
                <li class="nav-item">
                  <a class="nav-link text-white" href="#">Home</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link text-white" href="#about">About</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link text-white" href="#services">Services</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link text-white" href="#contact">Contact</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link text-white" href="#officials">Officials</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link text-white" href="#announcements">New & Announcements</a>
                </li>

                    <li class="nav-item">
                        <a class="nav-link text-white" data-bs-toggle="modal" data-bs-target="#feedbackModal" style="cursor: pointer;">Feedback</a>
                    </li>


          <!-- Profile Dropdown -->
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle d-flex align-items-center text-white" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              
           <img src="<?= $profileImage ?>" alt="Profile" width="32" height="32" class="rounded-circle me-2">
              <span>Welcome, <?= htmlspecialchars($_SESSION['full_name']) ?></span>
                
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
              <li><a class="dropdown-item" href="#">View Profile</a></li>
              <li><a class="dropdown-item" href="#">Settings</a></li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item" href="logout.php">Logout</a></li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </nav>
  
  


                <!-- Hero Section -->
<section class="py-5 bg-white">
    <div class="container">
      <div class="row align-items-center">
        <!-- Text Content -->
        <div class="col-md-6 mb-4 mb-md-0">
          <h1 class="fw-bold">E–BARANGAY PORTAL</h1>
          <h5 class="text-uppercase text-muted">Streamlining Local Government Services Online</h5>
          <p class="mt-3 mb-1">Bakhawan, Daanbantayan, Cebu</p>
          <p class="mb-1">Open Hours of Barangay: Monday–Friday (8AM – 5PM)</p>
          <p class="mb-3">barangaybakhawan@gmail.com / 0912345678</p>
          <a href="#about" class="btn btn-primary">About Us &rarr;</a>
        </div>
        <!-- Logo -->
        <div class="col-md-6 text-center">
          <img src="img/BAKHAWAN-removebg-preview.png" alt="Official Seal" class="img-fluid" style="max-width: 300px;">
        </div>
      </div>
    </div>
  </section>
  
  <!-- About Us Section -->
  <section id="about" class="py-5 bg-light">
    <div class="container">
      <h6 class="text-muted">About us</h6>
      <p class="lead">
        The E-Barangay Portal is an innovative digital platform designed to streamline local government services by bringing them online. This system aims to enhance accessibility, efficiency, and transparency in barangay operations, allowing residents to conveniently access services such as document requests, incident reporting, and community announcements from their devices. By leveraging technology, the platform reduces the need for in-person visits, making government transactions faster and more convenient for citizens.
      </p>
    </div>
  </section>
  



<!-- Expertise -->
<section class="py-5 bg-white">
    <div class="container">
      <h2 class="fw-bold text-black mb-4">Our expertise in <br> E-Barangay Portal</h2>
      <div class="row g-4">
        <!-- Card 1 -->
        <div class="col-md-4">
          <div class="p-4 h-100 rounded-3 text-white" style="background-color: #f97316;">
            <h5 class="fw-bold">System Development & <br> Customization</h5>
            <p class="mb-0 mt-2">Skilled in developing user-friendly interfaces and integrating features such as resident profiling, e-barangay Services</p>
          </div>
        </div>
        <!-- Card 2 -->
        <div class="col-md-4">
          <div class="p-4 h-100 rounded-3 text-white" style="background-color: #638eff;">
            <h5 class="fw-bold">User Training & Support</h5>
            <p class="mb-0 mt-2">Experienced in conducting training sessions for barangay officials and staff to ensure smooth adoption and daily use of the platform.</p>
          </div>
        </div>
        <!-- Card 3 -->
        <div class="col-md-4">
          <div class="p-4 h-100 rounded-3 text-white" style="background-color: #638eff;">
            <h5 class="fw-bold">E-Governance Advocacy</h5>
            <p class="mb-0 mt-2">Actively promotes digital transformation in local governance by aligning portal features with the DILG’s</p>
          </div>
        </div>
      </div>
    </div>
  </section>
  

<!-- Services -->
<section class="py-5 bg-light">
    <div class="container">
      <h2 class="fw-bold text-center mb-4">E–Barangay Services</h2>
      <div class="row g-4 justify-content-center">
        <!-- Service Card 1 -->
        <div class="col-md-4 col-lg-3">
          <div class="card text-center shadow-lg h-100 p-4">
            <img src="img/BAKHAWAN-removebg-preview.png" class="card-img-top mx-auto" style="width: 100px;" alt="Barangay Clearance">
            <div class="card-body">
              <h5 class="card-title fw-bold">Barangay Clearance</h5>
              <a href="#" class="btn btn-primary mt-2">Request</a>
            </div>
          </div>
        </div>
        <!-- Service Card 2 -->
        <div class="col-md-4 col-lg-3">
          <div class="card text-center shadow-lg h-100 p-4">
            <img src="img/BAKHAWAN-removebg-preview.png" class="card-img-top mx-auto" style="width: 100px;" alt="Barangay Certificate">
            <div class="card-body">
              <h5 class="card-title fw-bold">Barangay Certificate</h5>
              <a href="#" class="btn btn-primary mt-2">Request</a>
            </div>
          </div>
        </div>
        <!-- Service Card 3 -->
        <div class="col-md-4 col-lg-3">
          <div class="card text-center shadow-lg h-100 p-4">
            <img src="img/BAKHAWAN-removebg-preview.png" class="card-img-top mx-auto" style="width: 100px;" alt="Indigency">
            <div class="card-body">
              <h5 class="card-title fw-bold">Certificate of Indigency</h5>
              <a href="#" class="btn btn-primary mt-2">Request</a>
            </div>
          </div>
        </div>
        
        <div class="col-md-4 col-lg-3">
            <div class="card text-center shadow-lg h-100 p-4">
              <img src="img/BAKHAWAN-removebg-preview.png" class="card-img-top mx-auto" style="width: 100px;" alt="Indigency">
              <div class="card-body">
                <h5 class="card-title fw-bold">Certificate of Indigency</h5>
                <a href="#" class="btn btn-primary mt-2">Request</a>
              </div>
            </div>
          </div>
      </div>
    </div>
  </section>
  

<!-- Officials -->
<section class="py-5">
    <div class="container">
      <h2 class="fw-bold text-center mb-4">Barangay Officials</h2>
      <div class="position-relative">
        <!-- Navigation Buttons -->
        <button class="btn btn-primary position-absolute top-50 start-0 translate-middle-y slider-prev-btn">‹</button>
        <button class="btn btn-primary position-absolute top-50 end-0 translate-middle-y slider-next-btn">›</button>
  
        <div class="row g-4 justify-content-center slider-group">
            <!-- Card 1 -->
            <div class=" col-md-4 col-lg-3">
              <div class="card shadow-sm">
                <img src="img/Avatar11.jpg" class="card-img-top" alt="Barangay Captain">
                <div class="card-body text-center">
                  <h5 class="card-title mb-1">HON. Example Name 1</h5>
                  <p class="text-muted mb-0">Barangay Captain</p>
                </div>
              </div>
            </div>
            <!-- Card 2 -->
            <div class=" col-md-4 col-lg-3">
              <div class="card shadow-sm">
                <img src="img/Avatar11.jpg" class="card-img-top" alt="Barangay Treasurer">
                <div class="card-body text-center">
                  <h5 class="card-title mb-1">HON. Example Name 2</h5>
                  <p class="text-muted mb-0">Barangay Treasurer</p>
                </div>
              </div>
            </div>
            <!-- Card 3 -->
            <div class="col-md-4 col-lg-3">
              <div class="card shadow-sm">
                <img src="img/Avatar11.jpg" class="card-img-top" alt="Barangay Secretary">
                <div class="card-body text-center">
                  <h5 class="card-title mb-1">HON. Example Name 3</h5>
                  <p class="text-muted mb-0">Barangay Secretary</p>
                </div>
              </div>
            </div>
          </div>
      </div>
    </div>
  </section>

  
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const sliderGroup = document.querySelector('.slider-group');
      const prevBtn = document.querySelector('.slider-prev-btn');
      const nextBtn = document.querySelector('.slider-next-btn');
  
      const slides = [
        [
          { img: 'img/Avatar11.jpg', title: 'HON. Example Name 1', subtitle: 'Barangay Captain' },
          { img: 'img/Avatar11.jpg', title: 'HON. Example Name 2', subtitle: 'Barangay Treasurer' },
          { img: 'img/Avatar11.jpg', title: 'HON. Example Name 3', subtitle: 'Barangay Secretary' }
        ],
        [
          { img: 'img/Avatar11.jpg', title: 'HON. Kagawad Name 1', subtitle: 'Barangay Kagawad' },
          { img: 'img/Avatar11.jpg', title: 'HON. Kagawad Name 2', subtitle: 'Barangay Kagawad' },
          { img: 'img/Avatar11.jpg', title: 'HON. Kagawad Name 3', subtitle: 'Barangay Kagawad' }
        ]
      ];
  
      let currentSlideIndex = 0;
  
      const updateCards = () => {
        const currentSlide = slides[currentSlideIndex];
        const cards = sliderGroup.querySelectorAll('.card');
        cards.forEach((card, index) => {
          const img = card.querySelector('.card-img-top');
          const title = card.querySelector('.card-title');
          const subtitle = card.querySelector('.text-muted');
  
          img.src = currentSlide[index].img;
          title.textContent = currentSlide[index].title;
          subtitle.textContent = currentSlide[index].subtitle;
        });
      };
  
      prevBtn.addEventListener('click', () => {
        currentSlideIndex = (currentSlideIndex - 1 + slides.length) % slides.length;
        updateCards();
      });
  
      nextBtn.addEventListener('click', () => {
        currentSlideIndex = (currentSlideIndex + 1) % slides.length;
        updateCards();
      });
  
      // Initialize the first slide
      updateCards();
    });
  </script>
  

<!-- Announcements -->
<section class="py-5">
  <div class="container">
    <h3 class="text-center mb-4">Recent Announcements</h3>
    <div class="card">
      <img src="img/BAKHAWAN-removebg-preview.png" alt="Announcement" class="announcement-img">
      <div class="card-body">
        <h5 class="card-title">Flag Ceremony</h5>
        <p class="card-text">A successful flag ceremony was held with student participation.</p>
      </div>
    </div>
  </div>
</section>

<!-- Footer -->
<footer class="footer py-4 mt-5">
  <div class="container text-center">
    <p class="mb-1">© 2025 Barangay Bakhawan. All rights reserved.</p>
    <small>Developed by Angelo Ryan Ytang</small>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>




              <!-- Feedback Modal TEMPORARY -->
             <!-- Feedback Modal -->
<div class="modal fade" id="feedbackModal" tabindex="-1" aria-labelledby="feedbackModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered"> <!-- This centers the modal -->
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="feedbackModalLabel">Send Feedback using AES-256-CBC</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="submit_feedback.php" method="POST">
        <div class="modal-body">
          <div class="form-group">
            <label for="feedback">Your Message:</label>
            <textarea name="feedback" class="form-control" rows="4" required></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Submit Feedback</button>
        </div>
      </form>
    </div>
  </div>
</div>


</body>
</html>
