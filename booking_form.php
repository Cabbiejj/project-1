<?php
session_start();
include 'db_connect.php';

// Check if user is logged in
if (!isset($_SESSION['user_email'])) {
    header("Location: login.php");
    exit();
}

// Initialize variables
$name = $category = $people = $interest = $arrival_date = $start_date = $phone = $email = '';
$success = '';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $name = $_POST['name'];
    $category = $_POST['category'];
    $people = $_POST['people'];
    $interest = $_POST['interest'];
    $arrival_date = $_POST['arrival_date'];
    $start_date = $_POST['start_date'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];

    // Insert booking into the database
    $sql = "INSERT INTO booking (name, category, people, interest, arrival_date, start_date, phone, email, user_email) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssissssss", $name, $category, $people, $interest, $arrival_date, $start_date, $phone, $email, $_SESSION['user_email']);
    if ($stmt->execute()) {
        $success = "Booking successful!";
    } else {
        $success = "Error occurred while booking.";
    }
    $stmt->close();
    
    // Close the database connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Style/booking_form.css">
    <title>Booking Form</title>
</head>
<body>
    <h1>Booking Form</h1>
    <?php if ($success): ?>
        <p><?php echo $success; ?></p>
    <?php endif; ?>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="name">Name:</label><br>
        <input type="text" id="name" name="name" required><br>
        <label for="category">Category:</label><br>
        <input type="text" id="category" name="category" required><br>
        <label for="people">People:</label><br>
        <input type="number" id="people" name="people" required><br>
        <label for="interest">Interested to visit:</label><br>
        <input type="text" id="interest" name="interest" required><br>
        <label for="arrival_date">Arrival Date:</label><br>
        <input type="date" id="arrival_date" name="arrival_date" required><br>
        <label for="start_date">Start Tour Date:</label><br>
        <input type="date" id="start_date" name="start_date" required><br>
        <label for="phone">Phone Number:</label><br>
        <input type="text" id="phone" name="phone" required><br>
        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required><br><br>
        <input type="submit" value="Submit">
    </form>
</body>
</html>
