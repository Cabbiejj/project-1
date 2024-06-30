<?php
session_start();
include 'db_connect.php';

// Check if user is logged in
if (!isset($_SESSION['user_email'])) {
    header("Location: login.php");
    exit();
}

// Initialize variables
$name = $category = $people = $interest = $arrival_date = $start_date = $phone = $email = $country = '';
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
    $country = $_POST['country'];

    // Insert booking into the database
    $sql = "INSERT INTO booking (name, category, people, interest, arrival_date, start_date, phone, email, country, user_email) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssisssssss", $name, $category, $people, $interest, $arrival_date, $start_date, $phone, $email, $country, $_SESSION['user_email']);
    if ($stmt->execute()) {
        $success = "Booking successfully submitted!";
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
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="Style/booking_form.css"> 

    <title>Booking Form</title>
</head>
<body>
    <div class="container">
        <h1 class="top-center">Booking Form</h1>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="row">
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="category">Category:</label>
                    <input type="text" class="form-control" id="category" name="category" required>
                </div>
                <div class="form-group">
                    <label for="people">People:</label>
                    <input type="number" class="form-control" id="people" name="people" required>
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
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>

    <!-- The Modal -->
    <div id="successModal" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Success!</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p><?php echo $success; ?></p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        // Show the modal if there is a success message
        $(document).ready(function() {
            <?php if (!empty($success)): ?>
                $('#successModal').modal('show');
            <?php endif; ?>
        });

        // Redirect to profile page after closing modal
        $('#successModal').on('hidden.bs.modal', function () {
            window.location.replace("profile.php");
        });
    </script>
</body>
</html>
