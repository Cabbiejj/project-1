$(document).ready(function(){
    $('.header').height($(window).height());
    })
    
    
    function validateForm() {
        var password = document.getElementById("password").value;
        var confirmPassword = document.getElementById("confirmPassword").value;
        var passwordError = document.getElementById("passwordError");
        var fullName = document.getElementsByName("fullName")[0].value;
        var email = document.getElementsByName("email")[0].value;
        var phoneNumber = document.getElementsByName("phoneNumber")[0].value;
        var birthDate = document.getElementsByName("birthDate")[0].value;
        var gender = document.querySelector('input[name="gender"]:checked');
        var country = document.getElementById("country").value;
        var otherCountry = document.getElementById("otherCountry");
    
        // Password strength check
        var strength = checkPasswordStrength(password);
        if (strength === "weak") {
            passwordError.textContent = "Password is weak. Consider adding more characters and using numbers or special characters.";
            return false;
        } else {
            passwordError.textContent = "";
        }
    
        // Basic form validation
        if (!fullName || !email || !phoneNumber || !birthDate || !gender || !country || (country === "others" && !otherCountry.value)) {
            alert("Please fill out all required fields.");
            return false;
        }
    
        if (password !== confirmPassword) {
            passwordError.textContent = "Passwords do not match";
            return false;
        }
    
        return true;
    }
    
    function checkPasswordStrength(password) {
        // Password strength check logic here
        // You can implement your own logic or use a library like zxcvbn for this purpose
        // This function should return "weak", "medium", or "strong" based on the password strength
    }
    
    function handleCountryChange() {
        var country = document.getElementById("country").value;
        var otherCountry = document.getElementById("otherCountry");
    
        if (country === "others") {
            otherCountry.style.display = "block";
            otherCountry.required = true;
        } else {
            otherCountry.style.display = "none";
            otherCountry.required = false;
        }
    }
    