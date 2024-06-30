<?php
session_start();
include 'db_connect.php';

// Check if user is logged in
if (!isset($_SESSION['user_email'])) {
    header("Location: login.php");
    exit();
}

// Check if form data is received
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $bookingId = $_POST['bookingId'];
    $name = $_POST['editBookingName'];
    $category = $_POST['editBookingCategory'];
    $people = $_POST['editBookingPeople'];
    $interest = $_POST['editBookingInterest'];
    $arrival_date = $_POST['editBookingArrivalDate'];
    $start_date = $_POST['editBookingStartDate'];

    // Calculate total price based on category and number of people
    $totalPrice = calculateTotalPrice($category, $people);

    // Prepare update statement
    $sql_update = "UPDATE booking SET name=?, category=?, people=?, interest=?, arrival_date=?, start_date=?, total_price=? WHERE id=?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("ssisssdi", $name, $category, $people, $interest, $arrival_date, $start_date, $totalPrice, $bookingId);

    // Execute update
    if ($stmt_update->execute()) {
        echo "Booking updated successfully.";
    } else {
        echo "Error updating booking: " . $stmt_update->error;
    }

    // Close statement and connection
    $stmt_update->close();
    $conn->close();
} else {
    echo "Invalid request.";
}

// Function to calculate total price based on category and number of people
function calculateTotalPrice($category, $people) {
    switch ($category) {
        case '1':
            return 350 * $people;
        case '2':
            return 650 * $people;
        default:
            return 0; // Default to 0 if category is not recognized
    }
}
?>
