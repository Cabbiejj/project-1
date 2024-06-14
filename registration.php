<?php
include 'db_connect.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

$errors = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Escape user inputs for security
    $fullName = $conn->real_escape_string($_POST['fullName']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = $conn->real_escape_string($_POST['password']);
    $confirmPassword = $conn->real_escape_string($_POST['confirmPassword']);
    $phoneNumber = $conn->real_escape_string($_POST['phoneNumber']);
    $birthDate = $conn->real_escape_string($_POST['birthDate']);
    $gender = $conn->real_escape_string($_POST['gender']);
    $country = ($_POST['country'] == 'others') ? $conn->real_escape_string($_POST['otherCountry']) : $conn->real_escape_string($_POST['country']);

    // Check if email exists
    $check_email_query = "SELECT * FROM user WHERE email = '$email'";
    $result = $conn->query($check_email_query);
    if ($result->num_rows > 0) {
        $errors['email'] = "This email address is already registered.";
    }

    if ($password !== $confirmPassword) {
        $errors['password'] = "Passwords do not match.";
    }

    if (strlen($password) < 8) {
        $errors['password_length'] = "Password must be at least 8 characters long.";
    }

    if (count($errors) === 0) {
        // Hash the password before storing
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // SQL insert statement
        $sql = "INSERT INTO user (name, email, password, phone, birthday, gender, address) 
                VALUES ('$fullName', '$email', '$hashed_password', '$phoneNumber', '$birthDate', '$gender', '$country')";

        if ($conn->query($sql) === TRUE) {
            // Redirect the user to another page if needed
            header("Location: login.php");
            exit(); // Stop executing the rest of the code after redirection
        } else {
            $errors['db'] = "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

// Close database connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <link rel="stylesheet" href="Style/regist.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <title>Registration</title>
    <style>
        .error-message {
            color: red;
            font-size: 0.8rem;
        }
    </style>
</head>
<body>
    <section class="container">
        <a href="login.php" class="back-button"><i class="fas fa-arrow-left"></i></a>
        <header>Registration Form</header>
        
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="form" method="post" onsubmit="return validateForm()">
            <div class="input-box">
                <label for="fullName">Full Name</label>
                <input type="text" name="fullName" id="fullName" placeholder="Enter full name" required />
                <?php if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($errors['fullName'])) { ?>
                    <span class="error-message"><?php echo $errors['fullName']; ?></span>
                <?php } ?>
            </div>
            <div class="input-box">
                <label for="email">Email Address</label>
                <input type="email" name="email" id="email" placeholder="Enter email address" required />
                <?php if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($errors['email'])) { ?>
                    <span class="error-message"><?php echo $errors['email']; ?></span>
                <?php } ?>
            </div>
            <div class="input-box">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter password" required />
                <?php if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($errors['password'])) { ?>
                    <span class="error-message"><?php echo $errors['password']; ?></span>
                <?php } ?>
                <?php if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($errors['password_length'])) { ?>
                    <span class="error-message"><?php echo $errors['password_length']; ?></span>
                <?php } ?>
            </div>
            <div class="input-box">
                <label for="confirmPassword">Confirm Password</label>
                <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm password" required />
                <?php if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($errors['password'])) { ?>
                    <span class="error-message"><?php echo $errors['password']; ?></span>
                <?php } ?>
            </div>
            <div class="column">
                <div class="input-box">
                    <label for="phoneNumber">Phone Number</label>
                    <input type="text" name="phoneNumber" id="phoneNumber" placeholder="Enter phone number with country code" required />
                </div>
                <div class="input-box">
                    <label for="birthDate">Birth Date</label>
                    <input type="date" name="birthDate" id="birthDate" placeholder="Enter birth date" required />
                </div>
            </div>
            <div class="gender-box">
                <h3>Gender</h3>
                <div class="gender-option">
                    <div class="gender">
                        <input type="radio" id="check-male" name="gender" value="male" />
                        <label for="check-male">Male</label>
                    </div>
                </div>
                <div class="gender-option">
                    <div class="gender">
                        <input type="radio" id="check-female" name="gender" value="female" />
                        <label for="check-female">Female</label>
                    </div>
                </div>
                <div class="gender-option">
                    <div class="gender">
                        <input type="radio" id="check-other" name="gender" value="preferNotToSay" />
                        <label for="check-other">Prefer not to say</label>
                    </div>
                </div>
            </div>
            <div class="input-box address">
                <label for="country">Nationality</label>
                <div class="column">
                    <div class="select-box">
                        <select name="country" id="country" onchange="handleCountryChange()">
                            <option hidden>Country</option>
                            <option value="Indonesia">Indonesia</option>
                            <option value="Japan">Japan</option>
                            <option value="India">India</option>
                            <option value="Nepal">Nepal</option>
                            <option value="America">America</option>
                            <option value="Malaysia">Malaysia</option>
                            <option value="others">Others</option>
                        </select>
                    </div>
                    <input type="text" name="otherCountry" id="otherCountry" placeholder="Enter your country" style="display:none;" />
                </div>
            </div>
            <?php if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($errors['db'])) { ?>
                <div class="error-message"><?php echo $errors['db']; ?></div>
            <?php } ?>
            <button type="submit">Submit</button>
        </form>
    </section>

    <script>
        function validateForm() {
            var password = document.getElementById("password").value;
            var confirmPassword = document.getElementById("confirmPassword").value;
            var passwordError = document.getElementById("passwordError");

            if (password !== confirmPassword) {
                alert("Passwords do not match");
                return false;
            } else if (password.length < 8) {
                alert("Password must be at least 8 characters long");
                return false;
            }
            return true;
        }

        function handleCountryChange() {
            var country = document.getElementById("country").value;
            var otherCountry = document.getElementById("otherCountry");

            if (country === 'others') {
                otherCountry.style.display = "block";
                otherCountry.required = true;
            } else {
                otherCountry.style.display = "none";
                otherCountry.required = false;
            }
        }
    </script>
</body>
</html>
