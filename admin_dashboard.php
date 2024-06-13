<?php
session_start();
include 'db_connect.php';

// Check if email is set and ends with "@admin.com"


// Retrieve all bookings from the database
$sql = "SELECT * FROM booking";
$result = $conn->query($sql);

if (!$result) {
    echo "Error in SQL query: " . $conn->error;
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="Style/admin_dashboard.css">
</head>
<body>
    <div class="container">
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
                    <th>Assign Member</th>
                    <th>Description</th>
                    <th>Action</th>
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
                        <td>
                            <select>
                                <option value="">Select Member</option>
                                <option value="member1">Member 1</option>
                                <option value="member2">Member 2</option>
                                <option value="member3">Member 3</option>
                                <!-- Add more members as needed -->
                            </select>
                        </td>
                        <td>
                            <button onclick="showDescription('<?php echo $row['description']; ?>')">Description</button>
                        </td>
                        <td><input type="checkbox"></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p>No bookings found.</p>
        <?php endif; ?>
    </div>

    <script>
        function showDescription(description) {
            alert(description); // Change this to display the description in a modal or popover
        }
    </script>
</body>
</html>
