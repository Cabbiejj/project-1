<?php
session_start();
include 'db_connect.php';

// Check if user is logged in
if (!isset($_SESSION['user_email'])) {
    header("Location: login.php");
    exit();
}

// Retrieve user information from the database
$user_email = $_SESSION['user_email'];
$sql = "SELECT name, phone, email, birthday, nationality FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_email);
$stmt->execute();
$stmt->store_result();

// Check if user exists
if ($stmt->num_rows == 1) {
    $stmt->bind_result($name, $phone, $email, $birthday, $nationality);
    $stmt->fetch();
} else {
    // Handle error if user doesn't exist
    // You can redirect to an error page or display a message
    echo "Error: User not found";
    exit();
}

// Close the database connection
$stmt->close();
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="Style/profile.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>

<nav class="navbar">
    <a class="navbar-brand" href="#">Your Website</a>
    <div class="collapse navbar-collapse" id="main-navigation">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="index.html">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="about.html">About</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="contact.html">Contact</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="login.php">Login</a>
            </li>
        </ul>
    </div>
</nav>

<div class="container">
    <h1>Welcome, <?php echo $name; ?></h1>
    
    <img class="profile-img" src="profile-image.jpg" alt="Profile Image">

    <ul class="user-info">
        <li><i class="fas fa-user"></i> <?php echo $name; ?></li>
        <li><i class="fas fa-phone"></i> <?php echo $phone; ?></li>
        <li><i class="fas fa-envelope"></i> <?php echo $email; ?></li>
        <li><i class="fas fa-birthday-cake"></i> <?php echo $birthday; ?></li>
        <li><i class="fas fa-globe"></i> <?php echo $nationality; ?></li>
        <!-- Add more user information with icons here if needed -->
    </ul>

    <!-- Add booking form here or any other content -->

    <form action="logout.php" method="post">
        <input type="submit" value="Logout">
    </form>
</div>

<footer>
    <div class="container">
        <p>&copy; 2024 Your Website. All rights reserved.</p>
    </div>
</footer>

</body>
</html>
