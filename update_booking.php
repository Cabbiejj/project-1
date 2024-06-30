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

    // Prepare update statement
    $sql_update = "UPDATE booking SET name=?, category=?, people=?, interest=?, arrival_date=?, start_date=? WHERE id=?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("ssisssi", $name, $category, $people, $interest, $arrival_date, $start_date, $bookingId);

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
?>
