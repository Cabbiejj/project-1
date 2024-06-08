<?php
// Database configuration
$dbhost = "localhost";
$dbname = "project1";
$dbuser = "root";
$dbpass = "";

// Establishing a connection to the database
$conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Escape user inputs for security
    $fullName = $conn->real_escape_string($_POST['fullName']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = $conn->real_escape_string($_POST['password']);
    $phoneNumber = $conn->real_escape_string($_POST['countryCode'] . $_POST['phoneNumber']);
    $birthDate = $conn->real_escape_string($_POST['birthDate']);
    $gender = $conn->real_escape_string($_POST['gender']);
    $country = ($_POST['country'] == 'others') ? $conn->real_escape_string($_POST['otherCountry']) : $conn->real_escape_string($_POST['country']);

    // SQL insert statement
    $sql = "INSERT INTO user (name, email, password, phone, birthday, gender, adress) 
            VALUES ('$fullName', '$email', '$password', '$phoneNumber', '$birthDate', '$gender', '$country')";

    if ($conn->query($sql) === TRUE) {
        echo "Registration successful!";
        // You can redirect the user to another page if needed
        // header("Location: success.php");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Close database connection
$conn->close();
?>
