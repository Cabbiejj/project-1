<?php
session_start();
include 'db_connect.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_email'])) {
    header("Location: admin_login.php");
    exit();
}

// Retrieve all bookings from the database
$sql = "SELECT * FROM booking";
$result = $conn->query($sql);

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Style/admin_dashboard.css" /> 
    <title>Admin Dashboard</title>
</head>
<body>
    <h1>Welcome Admin!</h1>
    
    <h2>Bookings Confirmed by Users:</h2>
    <?php if ($result->num_rows > 0): ?>
        <table>
            <tr>
                <th>Name</th>
                <th>Category</th>
                <th>People</th>
                <th>Interested to visit</th>
                <th>Arrival Date</th>
                <th>Start Tour Date</th>
                <th>Phone Number</th>
                <th>Email</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['category']; ?></td>
                    <td><?php echo $row['people']; ?></td>
                    <td><?php echo $row['interest']; ?></td>
                    <td><?php echo $row['arrival_date']; ?></td>
                    <td><?php echo $row['start_date']; ?></td>
                    <td><?php echo $row['phone']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No bookings found.</p>
    <?php endif; ?>
</body>
</html>
