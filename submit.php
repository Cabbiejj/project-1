<?php
    // Database connection details
    $dbname = "project1";
    $dbuser = "root";
    $dbpass = "";
    $dbhost = "localhost";

    // Create a new MySQLi instance
    $conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Retrieve form data
        $name = $_POST['name'];
        $email = $_POST['email'];
        $message = $_POST['message'];

        // Prepare an SQL statement to insert data
        $stmt = $conn->prepare("INSERT INTO get_in_touch (name, email, message) VALUES (?, ?, ?)");

        // Check if the statement was prepared successfully
        if ($stmt === false) {
            die("Error preparing statement: " . $conn->error);
        }

        // Bind the variables to the statement as parameters
        $stmt->bind_param("sss", $name, $email, $message);

        // Execute the statement
        if ($stmt->execute()) {
            // Redirect to index.html with success status
            header("Location: index.html?status=success");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    }

    // Close the connection
    $conn->close();
?>
