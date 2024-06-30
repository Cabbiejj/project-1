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

// Ensure id parameter is provided
if (!isset($_GET['id'])) {
    echo "Booking id not provided.";
    exit();
}

$id = $_GET['id'];

// Initialize variables
$name = $category = $people = $interest = $arrival_date = $start_date = $phone = $email = $country = $total_price = '';

// Prepare SQL statement to fetch booking details
$sql = "SELECT id, name, category, people, interest, arrival_date, start_date, phone, email, country, total_price FROM booking WHERE id = ?";
$stmt = $conn->prepare($sql);

// Bind parameter and execute query
$stmt->bind_param("i", $id);
$stmt->execute();

// Store result
$stmt->store_result();

// Check if booking exists
if ($stmt->num_rows == 1) {
    // Bind result variables
    $stmt->bind_result($id, $name, $category, $people, $interest, $arrival_date, $start_date, $phone, $email, $country, $total_price);
    
    // Fetch data
    $stmt->fetch();
} else {
    // Handle case where no booking with the specified id was found
    echo "Booking not found.";
    exit();
}

// Close statement
$stmt->close();

// Close database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jakarta Tour Guide - Booking Invoice</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Global styles */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border: 1px solid #ced4da;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .logo {
            display: flex;
            align-items: center;
        }
        .logo img {
            width: 50px;
            height: 50px;
            margin-right: 10px;
            border-radius: 50%;
        }
        .logo h1 {
            font-size: 1.5rem;
            margin: 0;
            color: #333;
        }
        .btn-back {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 8px 16px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 1rem;
            transition: background-color 0.3s;
        }
        .btn-back:hover {
            background-color: #0056b3;
            text-decoration: none;
        }
        .invoice-details {
            margin-top: 20px;
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }
        .invoice-details h2 {
            font-size: 1.8rem;
            color: #333;
            margin-bottom: 20px;
        }
        .invoice-details p {
            margin: 8px 0;
            color: #555;
            font-size: 1.1rem;
        }
        .btn-print {
            margin-top: 20px;
            text-align: center;
        }
        .btn-print button {
            background-color: #28a745;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 1.2rem;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .btn-print button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <a href="profile.php" class="btn-back">Back to Profile</a>
            <div class="logo">
                <img src="photo/logo.png" alt="Company Logo">
                <h1>Jakarta Tour Guide</h1>
            </div>
        </div>
        <div class="invoice-details">
            <h2>Booking Invoice</h2>
            <p><strong>Name:</strong> <?php echo htmlspecialchars($name); ?></p>
            <p><strong>Category:</strong> <?php echo $category == '1' ? '3 Days Package - $350 per person' : '7 Days Package - $650 per person'; ?></p>
            <p><strong>People:</strong> <?php echo htmlspecialchars($people); ?></p>
            <p><strong>Interested to visit:</strong> <?php echo htmlspecialchars($interest); ?></p>
            <p><strong>Arrival Date:</strong> <?php echo htmlspecialchars($arrival_date); ?></p>
            <p><strong>Start Tour Date:</strong> <?php echo htmlspecialchars($start_date); ?></p>
            <p><strong>Phone Number:</strong> <?php echo isset($phone) ? htmlspecialchars($phone) : ''; ?></p>
            <p><strong>Email:</strong> <?php echo isset($email) ? htmlspecialchars($email) : ''; ?></p>
            <p><strong>Country From:</strong> <?php echo isset($country) ? htmlspecialchars($country) : ''; ?></p>
            <p><strong>Total Price:</strong> $<?php echo isset($total_price) ? number_format($total_price, 2) : '0.00'; ?></p>
        </div>
        <div class="btn-print">
            <button onclick="window.print()">Print Invoice</button>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
