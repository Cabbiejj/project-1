<?php
session_start();
include 'db_connect.php';

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* Reset default browser styles */
        body {
            padding: 0;
            margin: 0;
            background: #f2f6e9;
            font-family: Arial, sans-serif;
        }

        .navbar {
            background: #6ab446;
        }

        .nav-link,
        .navbar-brand {
            font-size: 1rem;
            margin-top: 10px;
        }

        .nav-link {
            margin-right: 1em !important;
        }

        .nav-link:hover {
            color: #000;
        }

        .navbar-collapse {
            justify-content: flex-end;
        }

        /* Container Styles */
        .container {
            max-width: 1200px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        /* Heading Styles */
        h1, h2 {
            text-align: center;
            margin: 20px 0;
        }

        /* Table Styles */
        .table-container {
            overflow-x: auto; /* Enable horizontal scroll for smaller screens */
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 12px; /* Increased padding for better readability */
            text-align: left;
        }

        table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        table tr:hover {
            background-color: #f1f1f1;
        }

        /* Adjust specific columns */
        table th.arrival-date, table td.arrival-date,
        table th.start-date, table td.start-date {
            width: 150px; /* Set a specific width */
        }

        table th.assign-member, table td.assign-member {
            width: 300px; /* Set a specific width */
        }

        /* Form Element Styles */
        select {
            width: 100%; /* Make select box take full width of its container */
            max-width: 300px; /* Set max width to ensure it fits in one line */
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        button {
            padding: 8px 12px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 4px;
        }

        button:hover {
            background-color: #45a049;
        }

        /* Checkbox Styles */
        input[type="checkbox"] {
            transform: scale(1.5); /* Make the checkbox larger */
            accent-color: #4CAF50; /* Change the tick color to green */
        }

        /* Local Time Box Styles */
        #local-time-container {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        #local-time {
            padding: 10px;
            background-color: #d4edda;
            border: 1px solid #6ab446;
            border-radius: 8px;
            font-size: 3em;
        }

        /* Responsive Design */
        @media screen and (max-width: 768px) {
            .container {
                padding: 10px;
            }

            table th, table td {
                padding: 10px; /* Adjusted padding for smaller screens */
            }
        }
    </style>
    <script>
        function updateSelectText(selectElement) {
            var selectedText = selectElement.options[selectElement.selectedIndex].text;
            if (selectedText.startsWith('Member')) {
                selectElement.options[selectElement.selectedIndex].text = selectedText.split(' ')[1];
            }
        }

        function displayLocalTime() {
            var now = new Date();
            var hours = now.getHours().toString().padStart(2, '0');
            var minutes = now.getMinutes().toString().padStart(2, '0');
            var seconds = now.getSeconds().toString().padStart(2, '0');
            var formattedTime = hours + ':' + minutes + ':' + seconds;
            document.getElementById('local-time').textContent = formattedTime;
        }

        setInterval(displayLocalTime, 1000); // Update the time every second
    </script>
</head>
<body onload="displayLocalTime()">
    <div class="container">
        <a href="login.php" class="back-button"><i class="fas fa-arrow-left"></i></a>
        
        <div id="local-time-container">
            <div id="local-time"></div>
        </div>

        <h1>Welcome Admin!</h1>
        <br>
        <h2>Bookings Confirmed by Users:</h2>
        <div class="table-container">
            <?php if ($result->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>People</th>
                            <th>Interested</th>
                            <th class="arrival-date">Arrival Date</th>
                            <th class="start-date">Start Date</th>
                            <th>Country</th>
                            <th>Contact</th>
                            <th>Email</th>
                            <th class="assign-member">Assign Member</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['id']); ?></td>
                                <td><?= htmlspecialchars($row['name']); ?></td>
                                <td><?= htmlspecialchars($row['category']); ?></td>
                                <td><?= htmlspecialchars($row['people']); ?></td>
                                <td><?= htmlspecialchars($row['interest']); ?></td>
                                <td class="arrival-date"><?= htmlspecialchars($row['arrival_date']); ?></td>
                                <td class="start-date"><?= htmlspecialchars($row['start_date']); ?></td>
                                <td><?= htmlspecialchars($row['country']); ?></td>
                                <td><?= htmlspecialchars($row['phone']); ?></td>
                                <td><?= htmlspecialchars($row['email']); ?></td>
                                <td class="assign-member">
                                    <select onchange="updateSelectText(this)">
                                        <option value="">Select Member</option>
                                        <option value="member1">Member 1</option>
                                        <option value="member2">Member 2</option>
                                        <option value="member3">Member 3</option>
                                        <!-- Add more members as needed -->
                                    </select>
                                </td>
                                <td>
                                    <form action="delete_booking.php" method="post">
                                        <input type="hidden" name="booking_id" value="<?= $row['id']; ?>">
                                        <button type="submit" onclick="return confirm('Are you sure you want to delete this booking?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No bookings found.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
