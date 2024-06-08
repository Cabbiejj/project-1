<?php
// Database configuration
$dbhost = "localhost";
$dbname = "project1";
$dbuser = "root";
$dbpass = "";

// Establishing a connection to the database
$conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to retrieve user information
$sql = "SELECT * FROM users WHERE id = '1'"; // Assuming user ID 1, change as needed

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output data of each row
    while($row = $result->fetch_assoc()) {
        $fullName = $row["full_name"];
        $email = $row["email"];
        $phoneNumber = $row["phone"];
        $birthDate = $row["birthday"];
        $gender = $row["gender"];
        $address = $row["address"];
    }
} else {
    echo "0 results";
}

// Close database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="home.html">Home</a></li>
                <li><a href="contact.html">Contact</a></li>
                <li><a href="about.html">About</a></li>
                <li><a href="profile.html" class="active">Profile</a></li>
            </ul>
        </nav>
    </header>

    <div class="container">
        <div class="profile">
            <h1>User Profile</h1>
            <div class="profile-info">
                <label for="fullName">Full Name:</label>
                <p><?php echo $fullName; ?></p>

                <label for="email">Email:</label>
                <p><?php echo $email; ?></p>

                <label for="phoneNumber">Phone Number:</label>
                <p><?php echo $phoneNumber; ?></p>

                <label for="birthDate">Birth Date:</label>
                <p><?php echo $birthDate; ?></p>

                <label for="gender">Gender:</label>
                <p><?php echo $gender; ?></p>

                <label for="address">Address:</label>
                <p><?php echo $address; ?></p>
            </div>
        </div>

        <div class="booking">
            <h2>Book a Tour Guide</h2>
            <form action="submit_booking.php" method="post">
                <label for="date">Select Date:</label>
                <input type="date" id="date" name="date" required>

                <label for="message">Additional Message:</label>
                <textarea id="message" name="message" rows="4"></textarea>

                <button type="submit">Book Guide</button>
            </form>
        </div>
    </div>
</body>
</html>
