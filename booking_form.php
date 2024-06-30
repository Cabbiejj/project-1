<?php
session_start();
include 'db_connect.php';

// Check if user is logged in
if (!isset($_SESSION['user_email'])) {
    header("Location: login.php");
    exit();
}

// Initialize variables
$name = $people = $interest = $arrival_date = $start_date = $phone = $email = $country = '';
$category = '';
$total_price = 0;
$success = '';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $name = $_POST['name'];
    $people = $_POST['people'];
    $interest = $_POST['interest'];
    $arrival_date = $_POST['arrival_date'];
    $start_date = $_POST['start_date'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $country = $_POST['country'];
    $category = $_POST['category'];

    // Calculate total price based on category and number of people
    $total_price = calculateTotalPrice($category, $people);

    // Insert booking into the database
    $sql = "INSERT INTO booking (name, category, people, interest, arrival_date, start_date, phone, email, country, user_email, total_price) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssisssssssd", $name, $category, $people, $interest, $arrival_date, $start_date, $phone, $email, $country, $_SESSION['user_email'], $total_price);
    if ($stmt->execute()) {
        // Update total price for the booking in the database
        $booking_id = $stmt->insert_id;
        $update_sql = "UPDATE booking SET total_price = ? WHERE id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("di", $total_price, $booking_id);
        $update_stmt->execute();

        // Redirect to invoice page on successful booking
        header("Location: invoice.php?id=" . $booking_id);
        exit();
    } else {
        $success = "Error occurred while booking.";
    }
    $stmt->close();

    // Close the database connection
    $conn->close();
}

// Function to calculate total price based on category and number of people
function calculateTotalPrice($category, $people) {
    switch ($category) {
        case '1':
            return 350 * $people;
        case '2':
            return 650 * $people;
        default:
            return 0;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="Style/booking_form.css"> 

    <title>Booking Form</title>
</head>
<body>
    <div class="container">
        <h1 class="top-center">Booking Form</h1>
        <form id="bookingForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="row">
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="category">Category:</label>
                    <select class="form-control" id="category" name="category" required>
                        <option value="">Select Category</option>
                        <option value="1">3 Days Package - $350 per person</option>
                        <option value="2">7 Days Package - $650 per person</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="people">People:</label>
                    <input type="number" class="form-control" id="people" name="people" min="1" required>
                </div>
                <div class="form-group">
                    <label for="interest">Interested to visit:</label>
                    <input type="text" class="form-control" id="interest" name="interest" required>
                </div>
                <div class="form-group">
                    <label for="arrival_date">Arrival Date:</label>
                    <input type="date" class="form-control" id="arrival_date" name="arrival_date" required>
                </div>
                <div class="form-group">
                    <label for="start_date">Start Tour Date:</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" required>
                </div>
                <div class="form-group">
                    <label for="phone">Phone Number:</label>
                    <input type="text" class="form-control" id="phone" name="phone" required>
                </div>
                <div class="form-group">
                    <label for="country">Country From:</label>
                    <input type="text" class="form-control" id="country" name="country" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="total_price">Total Price:</label>
                    <input type="text" class="form-control" id="total_price" name="total_price" value="$<?php echo $total_price; ?>" readonly>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        // Update total price based on category and number of people
        $('#category, #people').change(function() {
            var category = $('#category').val();
            var people = $('#people').val();
            var total = 0;
            
            switch (category) {
                case '1':
                    total = 350 * people;
                    break;
                case '2':
                    total = 650 * people;
                    break;
                default:
                    total = 0;
                    break;
            }
            
            $('#total_price').val('$' + total);
        });
    </script>
</body>
</html>
