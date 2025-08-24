<?php
session_start();
include 'dbconnect.php';

// Ensure the user is logged in
if (!isset($_SESSION['user'])) {
    header('Location: login.html');
    exit();
}

$username = $_SESSION['user']['username']; // Fetch the username from the session

// Get the user_id based on the username
$query = "SELECT user_id FROM users WHERE username = ?";
$stmt = $pdo->prepare($query);
$stmt->execute([$username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "User not found.";
    exit();
}

$user_id = $user['user_id']; // Use this user_id for the insert operation

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars($_POST['name']);
    $type = htmlspecialchars($_POST['type']);
    $age = (int)$_POST['age'];
    $notes = htmlspecialchars($_POST['notes']);

    // Handle image upload
    $image = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $allowed_extensions = ['jpg', 'jpeg', 'png'];
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_name = basename($_FILES['image']['name']);
        $image_extension = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));

        if (in_array($image_extension, $allowed_extensions)) {
            $upload_dir = 'C:/xampp/htdocs/webpet/uploads/userspets/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            // Rename the image file to include user_id and timestamp for uniqueness
            $new_image_name = 'pet_' . $user_id . '_' . time() . '.' . $image_extension;
            $image_path = $upload_dir . $new_image_name;

            if (move_uploaded_file($image_tmp_name, $image_path)) {
                $image = $new_image_name; // Store only the new filename in the database
            } else {
                echo "Failed to upload the image.";
                exit();
            }
        } else {
            echo "Invalid file type. Only JPG, JPEG, and PNG files are allowed.";
            exit();
        }
    }

    // Insert into the database
    $query = "INSERT INTO pets (user_id, name, type, age, notes, image) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($query);

    try {
        $stmt->execute([$user_id, $name, $type, $age, $notes, $image]);
        // Redirect back to profile dashboard
        header('Location: profile.php');
        exit();
    } catch (PDOException $e) {
        echo "Error adding pet: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add a New Pet</title>
    <link rel="stylesheet" href="profile.css">
</head>
<body>
<nav>
    <div class="top-bar">
        <a href="index.php#home" class="nav-logo">
            <img src="imgs/logo.png" alt="Logo" class="logo-image">
            <b>MY</b>Pet
        </a>
        <div class="nav-right">
            <a href="index.php#about">About</a>
            <a href="index.php#Contact">Contact</a>
            <a href="petdisplay.php">Our Pets</a>
            <a href="services.php">Services</a>
            <a href="learn.php">Learn</a>
            <a href="donation.php">Donate</a>
            <?php
            if (isset($_SESSION['user'])): 
                echo '<a href="profile.php">' . htmlspecialchars($_SESSION['user']['username']) . '</a>';
            else: 
                echo '<a href="login.html">Login</a>';
            endif;
            ?>
        </div>
    </div>
</nav>

<div class="container">
    <div class="profile-container">
        <h3>Add a New Pet</h3>
        <form action="" method="POST" enctype="multipart/form-data">
            <label for="name">Pet Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="type">Type:</label>
            <input type="text" id="type" name="type" required>

            <label for="age">Age:</label>
            <input type="number" id="age" name="age" required>

            <label for="notes">Notes:</label>
            <textarea id="notes" name="notes"></textarea>

            <label for="image">Pet Image:</label>
            <input type="file" id="image" name="image" accept=".jpg,.jpeg,.png">

            <button type="submit">Add Pet</button>
        </form>
        <a href="profile.php">Back to Dashboard</a>
    </div>
</div>
</body>
</html>
