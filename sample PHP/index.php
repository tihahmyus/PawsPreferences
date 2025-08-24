<?php session_start(); ?>

<!DOCTYPE html>
<html>
<head>
<title>MyPet</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<link rel="stylesheet"  href="index.css?v=1.0">
</head>

<body>

  <nav>
    <div class="top-bar">
      <a href="index.php#home" class="nav-logo">
        <img src="imgs/logo.png" alt="Logo" class="logo-image">
        <b>MY</b>Pet</a>
      <!-- Float links to the right. Hide them on small screens -->
      <div class="nav-right">
        <a href="index.php#about">About</a>
        <a href="index.php#Contact">Contact</a>
        <a href="petdisplay.php">Our Pets</a>
        <a href="services.php">Services</a>
        <a href="learn.php">Learn</a>
        <a href="donation.php">Donate</a>
        <?php
        if (isset($_SESSION['user'])): 
            echo '<a href="profile.php"><i class="fas fa-user-circle profile-icon"></i></a>';
        else: 
            echo '<a href="login.html">Login</a>';
        endif;
        ?>
        </div>
        <div class="hamburger" onclick="toggleMenu()">&#9776;</div>
    </div>
</nav>

<!-- Mobile Menu -->
    <div class="mobile-menu">
        <a href="index.php#about">About</a>
        <a href="index.php#Contact">Contact</a>
        <a href="petdisplay.php">Our Pets</a>
        <a href="services.php">Services</a>
        <a href="learn.php">Learn</a>
        <a href="donation.php">Donate</a>
        <?php
        if (isset($_SESSION['user'])): 
            echo '<a href="profile.php"><i class="fas fa-user-circle profile-icon"></i></a>';
        else: 
            echo '<a href="login.html">Login</a>';
        endif;
        ?>
    </div>

<!-- Header -->
<header class="header-container" id="home">
    <img class="header-image" src="imgs/home.jpg" alt="Architecture">
    <div class="header-overlay">
        <h1>
        <span class="highlight"><b>MY</b></span>
        <span class="subtext">Pet</span>
        </h1>
    </div>
</header>

<!-- Page content -->
<div class="container" id="about">
  <h3 class="section-title">About</h3>
  <p>Welcome to MyPet, where we believe that every pet deserves loving care, 
    no matter how busy your schedule may be. Our mission is to provide a seamless, trustworthy platform that connects pet owners
     with reliable pet sitters, groomers, trainers, and transportation services.</p>
  <p>We understand that being a pet owner comes with responsibilities, and we strive to make it easier by offering a variety of 
    services tailored to your pet’s unique needs. From ensuring your pet receives the best grooming to arranging safe and comfortable transportation, we have it covered.</p>
  <h4>Educational Resources</h4>
    <p>We go beyond just services by offering a comprehensive learning space for pet owners to deepen their understanding of pet care. Whether it’s vaccinations, hypoallergenic care,
     or Islamic taharah practices, we provide informative resources to support your pet’s well-being in all aspects. Our goal is to empower you with knowledge so you can provide the best possible care.</p>
     <h4>Pet Adoption & Data Management</h4>
     <p>Our platform also acts as a bridge between pet shelters and potential adopters, helping to find loving homes for pets in need. Additionally, we prioritize convenience by allowing you to store your pet’s 
    data securely, reducing paperwork and ensuring everything is organized in one place.</p>
  <p>At MyPet, we are passionate about enhancing the lives of pets and their owners through trust, education, and community support.</p>
</div>

<div class="container">
<div class="row">
  <div class="col">
    <img src="imgs/connect.jpg" alt="Connect">
    <h3>Connect</h3>
    <p>We help you to connect to your potential paw buddies.</p>
    
  </div>
  <div class="col">
    <img src="imgs/shelter.jpeg" alt="Shelter">
    <h3>Shelter</h3>
    <p>We aim to give our paw friends a chance to receive love.</p>
    
  </div>
  <div class="col">
    <img src="imgs/insights.jpg" alt="Insights">
    <h3>Learn</h3>
    <p>We act as a platform for paw parents to learn about their companions.</p>
   
  </div>
  <div class="col">
    <img src="imgs/aid.jpg" alt="Aid">
    <h3>Aid</h3>
    <p>We offer aid to paw parents giving the best possible care to their pets.</p>
    
  </div>
</div>
</div>


<!-- Contact Section -->
  <div class="container" id="Contact">
    <h3 class="section-title">Inquiries and Suggestions</h3>
    <p>Thank you for considering MyPet for your pet care needs! 
      We are excited to learn more about how we can assist you and your furry friends. To ensure 
      we provide the best possible service, please fill out the form below with your inquiry details. Once submitted, 
      one of our team members will review your request and reach out to you via email with more information.
    </p>
    <p>Your satisfaction and your pet’s well-being are our top priorities, and we look forward to helping you with 
      trusted pet care services tailored to your specific needs. Thank you for trusting us with your pet care journey!.</p>
  </div>

    <div class="container">
        <form action="submit-inquiries.php" method="POST">
          <input type="text" placeholder="Name" required name="Name">
          <input type="text" placeholder="Email" required name="Email">
          <input type="text" placeholder="Subject" required name="Subject">
          <textarea placeholder="Comment" required name="Comment" rows="5"></textarea>
          <button type="submit" class="button">SEND MESSAGE</button>
        </form> 
    </div>
    
    <div class="bottom-container">
      <img src="imgs/indexbottom.jpg" alt="Image" class="bottom-image">
      <div class="overlay"></div>
    </div>
    
    <!-- Footer -->
    <footer class="footer">
      <p>&copy; 2024 MyPet. All rights reserved.</p>
    </footer>
  
    <script src="toggle-index.js"></script>
   
  
</body>
</html>