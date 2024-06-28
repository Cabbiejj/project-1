<?php
session_start();
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['booking_id'])) {
    $booking_id = $_POST['booking_id'];

    // Prepare and execute SQL statement to delete the booking
    $sql = "DELETE FROM booking WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $booking_id);

    if ($stmt->execute()) {
        // Deletion successful, redirect to admin dashboard
        header("Location: admin_dashboard.php");
        exit();
    } else {
        // Error in deletion
        echo "Error deleting booking: " . $conn->error;
    }

    // Close statement and database connection
    $stmt->close();
    $conn->close();
} else {
    // Redirect if accessed directly without POST data
    header("Location: admin_dashboard.php");
    exit();
}
?>
