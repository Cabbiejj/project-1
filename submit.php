<?php
    session_start();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get form data and trim whitespace
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $message = trim($_POST['message']);

        // Validate form data
        if (empty($name) || empty($email) || empty($message)) {
            $_SESSION['error'] = "All fields are required.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = "Invalid email format.";
        } else {
            // Database connection parameters
            $servername = "localhost";
            $username = "root";
            $password = ""; 
            $database_name = "project1"; 

            // Create connection to the database
            $conn = new mysqli($servername, $username, $password, $database_name);

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Prepare and bind the SQL statement to prevent SQL injection
            $stmt = $conn->prepare("INSERT INTO get_in_touch (name, email, message) VALUES (?, ?, ?)");
            if ($stmt === false) {
                die("Prepare failed: " . $conn->error);
            }
            $stmt->bind_param("sss", $name, $email, $message);

            // Execute the statement
            if ($stmt->execute()) {
                $_SESSION['success'] = "Thank you for getting in touch!";
            } else {
                $_SESSION['error'] = "Error: " . $stmt->error;
            }

            // Close statement and connection
            $stmt->close();
            $conn->close();
        }

        // Redirect back to the form page
        header("Location: contact.html"); // Change to the appropriate page
        exit();
    }
?>
