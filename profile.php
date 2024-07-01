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

// Retrieve user information from the database
$user_email = $_SESSION['user_email'];
$sql_user = "SELECT name, phone, email, birthday, address FROM user WHERE email = ?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param("s", $user_email);
$stmt_user->execute();
$stmt_user->store_result();

// Check if user exists
if ($stmt_user->num_rows == 1) {
    $stmt_user->bind_result($name, $phone, $email, $birthday, $address);
    $stmt_user->fetch();
} else {
    // Handle error if user doesn't exist
    echo "Error: User not found";
    exit();
}

// Fetch booking information for the user
$sql_booking = "SELECT id, name, category, people, interest, arrival_date, start_date FROM booking WHERE user_email = ?";
$stmt_booking = $conn->prepare($sql_booking);
$stmt_booking->bind_param("s", $user_email);
$stmt_booking->execute();
$result_booking = $stmt_booking->get_result();
$bookings = $result_booking->fetch_all(MYSQLI_ASSOC);

// Close the database connections
$stmt_user->close();
$stmt_booking->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <style>
        /* Custom CSS styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
        }

        .navbar {
            background-color: #343a40;
        }

        .navbar-brand {
            color: #ffffff;
            font-size: 1.5rem;
        }

        .navbar-nav .nav-item .nav-link {
            color: #ffffff;
        }

        .navbar-nav .nav-item .nav-link:hover {
            color: #adb5bd;
        }

        .container {
            margin: 20px auto;
            padding: 20px;
            background-color: #ffffff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            max-width: 800px;
            text-align: center;
        }

        .profile-img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            margin: 20px auto;
            display: block;
        }

        .user-info {
            list-style-type: none;
            padding: 0;
            text-align: center;
        }

        .user-info li {
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .user-info li i {
            margin-right: 10px;
        }

        .bookings {
            margin-top: 20px;
            text-align: center;
        }

        .booking-item {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            text-align: left;
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 600px;
            width: 100%;
            margin: 10px auto;
        }

        .booking-details {
            flex: 1;
        }

        .edit-booking-btn {
            margin-left: 10px;
        }

        .print-invoice-btn {
            margin-left: 10px;
        }

        /* Modal Styles */
        .modal-header {
            background-color: #007bff;
            color: white;
            border-bottom: none;
        }

        .modal-body {
            padding: 20px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-md navbar-dark bg-dark">
        <h3 class="navbar-brand">Jakarta Tour Guide</h3>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#main-navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="main-navigation">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.html">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="about.html">About</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="contact.html">Contact</a>
                </li>
                <li class="nav-item">
                    <form action="logout.php" method="post" style="display: inline;">
                        <button type="submit" class="nav-link btn btn-link" style="color: #ffffff; padding: 9;">Logout</button>
                    </form>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <h2 class="text-center">User Profile</h2>
        <hr>
        <div class="text-center">
            <img src="photo/profile.png" alt="Profile Picture" class="profile-img">
            <h4><?php echo htmlspecialchars($name); ?></h4>
        </div>
        <ul class="user-info">
            <li><i class="fa fa-envelope"></i> <span><?php echo htmlspecialchars($email); ?></span></li>
            <li><i class="fa fa-phone"></i> <span><?php echo htmlspecialchars($phone); ?></span></li>
            <li><i class="fa fa-birthday-cake"></i> <span><?php echo htmlspecialchars($birthday); ?></span></li>
            <li><i class="fa fa-address-card"></i> <span><?php echo htmlspecialchars($address); ?></span></li>
        </ul>
        <a href="booking_form.php" class="btn btn-danger">Book Now</a>
        <hr>
        
        <div class="bookings">
            <h3>My Bookings</h3>
            <?php if (!empty($bookings)) : ?>
                <?php foreach ($bookings as $booking) : ?>
                    <div class="booking-item">
                        <div class="booking-details">
                            <h4><?php echo htmlspecialchars($booking['name']); ?></h4>
                            <p><strong>Category:</strong> <?php echo htmlspecialchars($booking['category']); ?></p>
                            <p><strong>People:</strong> <?php echo htmlspecialchars($booking['people']); ?></p>
                            <p><strong>Interest:</strong> <?php echo htmlspecialchars($booking['interest']); ?></p>
                            <p><strong>Arrival Date:</strong> <?php echo htmlspecialchars($booking['arrival_date']); ?></p>
                            <p><strong>Start Date:</strong> <?php echo htmlspecialchars($booking['start_date']); ?></p>
                        </div>
                        <div>
                            <button type="button" class="btn btn-primary edit-booking-btn" data-toggle="modal" data-target="#editBookingModal" data-booking-id="<?php echo $booking['id']; ?>">Edit Booking</button>
                             <a href="invoice.php?id=<?php echo $booking['id']; ?>" class="btn btn-secondary print-invoice-btn" target="_blank" data-id="<?php echo $booking['id']; ?>">Print Invoice</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <p>No bookings found.</p>
            <?php endif; ?>
        </div>
        
    </div>

    <!-- Edit Booking Modal -->
    <div class="modal fade" id="editBookingModal" tabindex="-1" role="dialog" aria-labelledby="editBookingModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editBookingModalLabel">Edit Booking</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editBookingForm">
                        <input type="hidden" id="bookingId" name="bookingId">
                        <div class="form-group">
                            <label for="editBookingName">Name</label>
                            <input type="text" class="form-control" id="editBookingName" name="editBookingName" required>
                        </div>
                        <div class="form-group">
                            <label for="editBookingCategory">Category</label>
                            <input type="text" class="form-control" id="editBookingCategory" name="editBookingCategory" required>
                        </div>
                        <div class="form-group">
                            <label for="editBookingPeople">People</label>
                            <input type="number" class="form-control" id="editBookingPeople" name="editBookingPeople" required>
                        </div>
                        <div class="form-group">
                            <label for="editBookingInterest">Interest</label>
                            <input type="text" class="form-control" id="editBookingInterest" name="editBookingInterest" required>
                        </div>
                        <div class="form-group">
                            <label for="editBookingArrivalDate">Arrival Date</label>
                            <input type="date" class="form-control" id="editBookingArrivalDate" name="editBookingArrivalDate" required>
                        </div>
                        <div class="form-group">
                            <label for="editBookingStartDate">Start Date</label>
                            <input type="date" class="form-control" id="editBookingStartDate" name="editBookingStartDate" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js" integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous"></script>

    <script>
        $(document).ready(function() {
            // Handle click on Edit Booking button to fill modal with booking data
            $('.edit-booking-btn').on('click', function() {
                var bookingId = $(this).data('booking-id');
                var booking = <?php echo json_encode($bookings); ?>;
                
                // Find the booking by id
                var selectedBooking = booking.find(function(item) {
                    return item.id == bookingId;
                });

                // Populate modal fields with booking data
                $('#bookingId').val(selectedBooking.id);
                $('#editBookingName').val(selectedBooking.name);
                $('#editBookingCategory').val(selectedBooking.category);
                $('#editBookingPeople').val(selectedBooking.people);
                $('#editBookingInterest').val(selectedBooking.interest);
                $('#editBookingArrivalDate').val(selectedBooking.arrival_date);
                $('#editBookingStartDate').val(selectedBooking.start_date);
            });

            // Handle form submission for editing booking
            $('#editBookingForm').on('submit', function(event) {
                event.preventDefault();

                // Collect form data
                var formData = $(this).serialize();

                // AJAX request to update booking
                $.ajax({
                    url: 'update_booking.php',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        // Reload page on success or handle response
                        alert('Booking updated successfully.');
                        location.reload(); // Reload page to reflect changes
                    },
                    error: function(xhr, status, error) {
                        // Handle error
                        console.error(xhr.responseText);
                        alert('Error updating booking. Please try again.');
                    }
                });
            });

            // Handle click on Print Invoice button
            $('.print-invoice-btn').on('click', function(event) {
        event.preventDefault();
        var id = $(this).data('id');
        console.log(id);  // For debugging
        window.open('invoice.php?id=' + id, '_blank');
            });
        });
    </script>

</body>
</html>
