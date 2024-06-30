<?php
session_start();
include 'db_connect.php';

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Initialize error message
$error_message = '';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate password length
    if (strlen($password) < 8) {
        $error_message = "Password must be at least 8 characters long.";
    } else {
        // SQL query to retrieve hashed password for the given email
        $sql = "SELECT password FROM user WHERE email = ?";
        
        // Prepare and execute the statement
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        // Check if a row is returned
        if ($stmt->num_rows == 1) {
            // Bind the result to a variable
            $stmt->bind_result($hashed_password);
            $stmt->fetch();

            // Verify the password using password_verify()
            if (password_verify($password, $hashed_password)) {
                // Password is correct
                $_SESSION['user_email'] = $email;

                // Extract email domain and redirect based on it
                $emailDomain = substr(strrchr($email, "@"), 1);
                header("Location: " . ($emailDomain === 'admin.com' ? 'admin_dashboard.php' : 'profile.php'));
                exit();
            } else {
                // Incorrect password
                $error_message = "Invalid email or password.";
            }
        } else {
            // User not found
            $error_message = "Invalid email or password.";
        }

        // Close the statement
        $stmt->close();
    }
}

// Close the database connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <link rel="stylesheet" href="Style/regist.css" />
    <link rel="stylesheet" href="Style/login.css" /> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <title>Login</title>
    <style>
        .error {
            color: red;
        }
        .footer {
            position: absolute;
            bottom: 0;
            width: 100%;
            text-align: center;
            background-color: #343a40;
            color: #fff;
            padding: 10px 0;
        }
    </style>
</head>
<body>
    <section class="container">
        <a href="index.html" class="back-button"><i class="fas fa-arrow-left"></i> </a>
        <header>Login</header>
        <?php if (!empty($error_message)) : ?>
            <div class="error"><?php echo $error_message; ?></div>
        <?php endif; ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="form" method="post" onsubmit="return validateForm()">
            <div class="input-box">
                <label>Email Address</label>
                <input type="text" name="email" id="email" placeholder="Enter email address" required />
            </div>
            <div class="input-box">
                <label>Password</label>
                <div class="password-field">
                    <input type="password" id="password" name="password" placeholder="Enter password" required />
                    <span class="error" id="passwordError"></span>
                </div>
            </div>
            <button type="submit">Login</button>
        </form>
        <div class="register-link">
            <p>Don't have an account? <a href="registration.php">Register now</a></p>
        </div>
    </section>

    <div class="footer">
        <p>&copy; 2024 Jakarta Tour Guide. All Rights Reserved.</p>
    </div>

    <script>
        function validateForm() {
            var password = document.getElementById("password").value;
            var passwordError = document.getElementById("passwordError");

            // Reset error message
            passwordError.textContent = "";

            // Validate password length
            if (password.length < 8) {
                passwordError.textContent = "Password must be at least 8 characters long";
                return false;
            }
            return true;
        }
    </script>
</body>
</html>
