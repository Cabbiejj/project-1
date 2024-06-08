<?php
session_start();

// Database connection
// Include database configuration file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Query to validate user credentials
    // Execute the query and check if the user exists

    if ($userExists) {
        // Set user session variables
        $_SESSION['user_id'] = $userId;
        $_SESSION['email'] = $email;
        
        // Redirect user based on their role
        if ($isAdmin) {
            header("Location: admin_dashboard.php");
            exit();
        } else {
            header("Location: profile.html");
            exit();
        }
    } else {
        // Redirect back to login page with error message
        header("Location: login.php?error=invalid_login");
        exit();
    }
}
?>
