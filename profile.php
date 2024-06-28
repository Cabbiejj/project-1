<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db_connect.php';

// Check if user is logged in
if (!isset($_SESSION['user_email'])) {
    header("Location: login.php");
    exit();
}

// Retrieve user information from the database
$user_email = $_SESSION['user_email'];
$sql = "SELECT name, phone, email, birthday, address FROM user WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_email);
$stmt->execute();
$stmt->store_result();

// Check if user exists
if ($stmt->num_rows == 1) {
    $stmt->bind_result($name, $phone, $email, $birthday, $address);
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <style>
        /* Custom CSS styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
        }

        .navbar {
            background-color: #343a40; /* Dark background color */
        }

        .navbar-brand {
            color: #ffffff; /* White text */
            font-size: 1.5rem; /* Larger font size */
        }

        .navbar-nav .nav-item .nav-link {
            color: #ffffff; /* White text */
        }

        .navbar-nav .nav-item .nav-link:hover {
            color: #adb5bd; /* Lighter text color on hover */
        }

        .container {
            margin: 20px auto;
            padding: 20px;
            background-color: #ffffff; /* White background */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            max-width: 600px;
        }

        .profile-img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            margin: 20px auto;
            display: block;
        }

        .user-info {
            list-style-type: none;
            padding: 0;
        }

        .user-info li {
            margin-bottom: 10px;
        }

        .user-info li i {
            margin-right: 10px;
        }

        .book-now-button {
            background-color: #28a745; /* Green button */
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 20px;
            display: block;
            width: 100%;
            text-align: center;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-md navbar-dark bg-dark">
        <h3 class="navbar-brand">Jakarta Tour Guide</h3>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#main-navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
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
                    <form action="logout.php" method="post" style="display: inline;">
                        <button type="submit" class="nav-link btn btn-link" style="color: #ffffff; padding: 100;">Logout</button>
                    </form>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <h2 class="text-center">User Profile</h2>
        <hr>
        <div class="text-center">
            <img src="photo/profile.png" alt="Profile Picture" class="profile-img">
            <h4><?php echo $name; ?></h4>
        </div>
        <ul class="user-info">
            <li><i class="fa fa-envelope"></i> <?php echo $email; ?></li>
            <li><i class="fa fa-phone"></i> <?php echo $phone; ?></li>
            <li><i class="fa fa-birthday-cake"></i> <?php echo $birthday; ?></li>
            <li><i class="fa fa-address-card"></i> <?php echo $address; ?></li>
        </ul>
        <hr>
        <a href="booking_form.php" class="book-now-button">Book Now</a>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js" integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous"></script>

</body>
</html>
