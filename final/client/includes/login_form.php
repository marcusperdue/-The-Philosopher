<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Philosopher Bookstore</title>
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="css/media.css">  
    <link rel="icon" type="image/png" href="./assets/images/favicon.png">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Abril+Fatface&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="login-container">
        <a href="index.php">
            <img src="./assets/images/logo.svg" alt="The Philosopher Bookstore" class="logo">
        </a>
        <div class="container" id="container">

 <!-- Login Form -->
        <div class="form-container sign-in-container">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" class="login-form" id="loginForm">
                    <h2>Login</h2>
                    <div id="login-success-message" style="color: green;">
                        <?php 
                        if (isset($_SESSION['success_message'])) {
                            echo $_SESSION['success_message'];
                            unset($_SESSION['success_message']);
                        }
                        ?>
                    </div>
                    <div id="login-error-message" style="color: red;"></div>
                    <div class="input-group">
                        <label for="username">Username:</label>
                        <input type="text" id="login-username" name="username" placeholder="Enter your username">
                        <span class="hint" id="login-username-hint"></span>
                    </div>
                    <div class="input-group">
                        <label for="password">Password:</label>
                        <input type="password" id="login-password" name="password" placeholder="Enter your password">
                        <span class="hint" id="login-password-hint"></span>
                    </div>
                    <button type="submit" name="login" id="login-btn">Login</button>
                </form>
            </div>


<!-- Sign-Up Form -->
            <div class="form-container sign-up-container">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" class="signup-form" id="signupForm">
                    <h2>Sign Up</h2>
                    <div id="form-error-message" style="color: red;"></div>
                    <div class="input-group">
                        <label for="first-name">First Name:</label>
                        <input type="text" id="first-name" name="first_name" placeholder="Enter your first name" data-hint="Enter your first name">
                        <span class="hint" id="first-name-hint"></span>
                    </div>
                    <div class="input-group">
                        <label for="last-name">Last Name:</label>
                        <input type="text" id="last-name" name="last_name" placeholder="Enter your last name" data-hint="Enter your last name">
                        <span class="hint" id="last-name-hint"></span>
                    </div>
                    <div class="input-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" placeholder="Enter your email" data-hint="Enter your email">
                        <span class="hint" id="email-hint"></span>
                    </div>
                    <div class="input-group">
                        <label for="new-username">Username:</label>
                        <input type="text" id="signup-username" name="username" placeholder="Choose a username" data-hint="Choose a username">
                        <span class="hint" id="signup-username-hint"></span>
                    </div>
                    <div class="input-group">
                        <label for="new-password">Password:</label>
                        <input type="password" id="new-password" name="password" placeholder="Choose a password" data-hint="Choose a password">
                        <span class="hint" id="new-password-hint"></span>
                                           </div>
                    <div class="input-group">
                           <input type="checkbox" id="privacy-policy" name="privacy_policy">
                           <label for="privacy-policy">I agree to the 
                               <span id="privacy-policy-link" class="privacy-policy-link">Privacy Policy</span>
                           </label>
                           <span class="hint" id="privacy-policy-hint"></span>
                    </div>
                            <button type="submit" name="signup" id="signup-btn">Sign Up</button>
                        </form>
                 </div>

<?php include 'privacy_policy_modal.php'; ?>



            <div class="overlay-container">
                <div class="overlay">
                    <div class="overlay-panel overlay-left">
                        <h1>Hello, Friend!</h1>
                        <p>Enter your personal details and start the journey with us</p>
                        <button class="ghost" id="signIn">Sign In</button>
                    </div>
                    <div class="overlay-panel overlay-right">
                        <h1>Welcome!</h1>
                        <p>To stay connected with us, please login with your personal info or</p>
                        <button class="ghost" id="signUp">Sign Up</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="back-button-container">
            <a href="index.php" class="back-button"><i class="fas fa-arrow-left"></i></a>
        </div>
    </div>

    <script>
document.addEventListener('DOMContentLoaded', function() {
    const privacyPolicyLink = document.getElementById('privacy-policy-link');
    const privacyPolicyModal = document.getElementById('privacyPolicyModal');
    const closeBtn = document.querySelector('.close');

    privacyPolicyLink.addEventListener('click', function() {
        privacyPolicyModal.style.display = 'block';
    });

    closeBtn.addEventListener('click', function() {
        privacyPolicyModal.style.display = 'none';
    });

    window.addEventListener('click', function(event) {
        if (event.target === privacyPolicyModal) {
            privacyPolicyModal.style.display = 'none';
        }
    });
});
</script>

    <script>

// Privacy Policy Modal functionality
    $(document).ready(function() {
        const signUpButton = document.getElementById('signUp');
        const signInButton = document.getElementById('signIn');
        const container = document.getElementById('container');

        let isUsernameValid = false;
        let isEmailValid = false;

        signUpButton.addEventListener('click', () => {
            container.classList.add("right-panel-active");
        });

        signInButton.addEventListener('click', () => {
            container.classList.remove("right-panel-active");
        });

// Function to check username availability
        function checkUsernameAvailability() {
            var username = document.getElementById("signup-username").value;
            if (username.trim() !== "") {


// Send an AJAX request to check user availability
                $.ajax({
                    url: "includes/checkAvailability.php",
                    method: "POST",
                    data: { username: username },
                    success: function(response) {
                        if (response === "username_taken") {
                            document.getElementById("signup-username-hint").innerText = "Username is already taken";
                            document.getElementById("signup-username-hint").style.color = "red";
                            isUsernameValid = false;
                        } else {
                            document.getElementById("signup-username-hint").innerText = "";
                            isUsernameValid = true;
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("Error checking username: " + error);
                        alert("Error checking username: " + error);
                        isUsernameValid = false;
                    }
                });
            } else {
                document.getElementById("signup-username-hint").innerText = "Please enter a username";
                document.getElementById("signup-username-hint").style.color = "red";
                isUsernameValid = false;
            }
        }

// Function to check email availability
        function checkEmailAvailability() {
            var email = document.getElementById("email").value;
            if (email.trim() !== "") {

// Send an AJAX request to check email availability
                $.ajax({
                    url: "includes/checkAvailability.php",
                    method: "POST",
                    data: { email: email },
                    success: function(response) {
                        if (response === "email_taken") {
                            document.getElementById("email-hint").innerText = "Email is already taken";
                            document.getElementById("email-hint").style.color = "red";
                            isEmailValid = false;
                        } else {
                            document.getElementById("email-hint").innerText = "";
                            isEmailValid = true;
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("Error checking email: " + error);
                        alert("Error checking email: " + error);
                        isEmailValid = false;
                    }
                });
            } else {
                document.getElementById("email-hint").innerText = "Please enter an email";
                document.getElementById("email-hint").style.color = "red";
                isEmailValid = false;
            }
        }



// event listeners to username and email input fields
        document.getElementById("signup-username").addEventListener("focus", function() {
            document.getElementById("signup-username-hint").innerText = "Enter your username";
            document.getElementById("signup-username-hint").style.color = "";
        });
        document.getElementById("signup-username").addEventListener("blur", function() {
            checkUsernameAvailability();
        });

        document.getElementById("email").addEventListener("focus", function() {
            document.getElementById("email-hint").innerText = "Enter your email";
            document.getElementById("email-hint").style.color = "";
        });
        document.getElementById("email").addEventListener("blur", function() {
            checkEmailAvailability();
        });

        document.getElementById("first-name").addEventListener("focus", function() {
            document.getElementById("first-name-hint").innerText = "Enter your first name";
            document.getElementById("first-name-hint").style.color = "";
        });
        document.getElementById("first-name").addEventListener("blur", function() {
            if (this.value.trim() === "") {
                document.getElementById("first-name-hint").innerText = "Please enter your first name";
                document.getElementById("first-name-hint").style.color = "red";
                this.style.borderColor = "red";
            } else {
                document.getElementById("first-name-hint").innerText = "";
                this.style.borderColor = "";
            }
        });

        document.getElementById("last-name").addEventListener("focus", function() {
            document.getElementById("last-name-hint").innerText = "Enter your last name";
            document.getElementById("last-name-hint").style.color = "";
        });
        document.getElementById("last-name").addEventListener("blur", function() {
            if (this.value.trim() === "") {
                document.getElementById("last-name-hint").innerText = "Please enter your last name";
                document.getElementById("last-name-hint").style.color = "red";
                this.style.borderColor = "red";
            } else {
                document.getElementById("last-name-hint").innerText = "";
                this.style.borderColor = "";
            }
        });

        document.getElementById("new-password").addEventListener("focus", function() {
            document.getElementById("new-password-hint").innerText = "Enter your password";
            document.getElementById("new-password-hint").style.color = "";
        });
        document.getElementById("new-password").addEventListener("blur", function() {
            if (this.value.trim() === "") {
                document.getElementById("new-password-hint").innerText = "Please enter a password";
                document.getElementById("new-password-hint").style.color = "red";
                this.style.borderColor = "red";
            } else {
                document.getElementById("new-password-hint").innerText = "";
                this.style.borderColor = "";
            }
        });


// Sign up form validation and submission
        document.getElementById("signupForm").addEventListener("submit", function(event) {
            let isValid = true;
            const firstName = document.getElementById("first-name").value;
            const lastName = document.getElementById("last-name").value;
            const password = document.getElementById("new-password").value;
            const privacyPolicy = document.getElementById("privacy-policy").checked;

            if (firstName.trim() === "") {
                document.getElementById("first-name-hint").innerText = "Please enter your first name";
                document.getElementById("first-name-hint").style.color = "red";
                isValid = false;
            }
            if (lastName.trim() === "") {
                document.getElementById("last-name-hint").innerText = "Please enter your last name";
                document.getElementById("last-name-hint").style.color = "red";
                isValid = false;
            }
            if (password.trim() === "") {
                document.getElementById("new-password-hint").innerText = "Please enter a password";
                document.getElementById("new-password-hint").style.color = "red";
                isValid = false;
            }
            if (!privacyPolicy) {
                document.getElementById("privacy-policy-hint").innerText = "Please agree to the Privacy Policy";
                document.getElementById("privacy-policy-hint").style.color = "red";
                isValid = false;
            }

            if (!isUsernameValid || !isEmailValid || !isValid) {
                event.preventDefault();
                document.getElementById("form-error-message").innerText = "Please fix the errors before submitting the form.";
            }  
        });



// Login form validation
    document.getElementById("loginForm").addEventListener("submit", function(event) {
        event.preventDefault();
        
        let isValid = true;
        const username = document.getElementById("login-username").value.trim();
        const password = document.getElementById("login-password").value.trim();

        if (username === "") {
            document.getElementById("login-username-hint").innerText = "Please enter your username";
            document.getElementById("login-username-hint").style.color = "red";
            document.getElementById("login-username").style.borderColor = "red";
            isValid = false;
        } else {
            document.getElementById("login-username-hint").innerText = "";
            document.getElementById("login-username").style.borderColor = "";
        }

        if (password === "") {
            document.getElementById("login-password-hint").innerText = "Please enter your password";
            document.getElementById("login-password-hint").style.color = "red";
            document.getElementById("login-password").style.borderColor = "red";
            isValid = false;
        } else {
            document.getElementById("login-password-hint").innerText = "";
            document.getElementById("login-password").style.borderColor = "";
        }

        if (isValid) {
            $.ajax({
                url: "<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>",
                method: "POST",
                data: { login: true, username: username, password: password },
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        window.location.href = "index.php";
                    } else {
                        document.getElementById("login-error-message").innerText = response.message;
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error during login: " + error);
                    document.getElementById("login-error-message").innerText = "An error occurred during login. Please try again.";
                }
            });
        } else {
            document.getElementById("login-error-message").innerText = "Please fix the errors before submitting the form.";
        }
    });


// Validate login username input
    document.getElementById("login-username").addEventListener("blur", function() {
        if (this.value.trim() === "") {
            document.getElementById("login-username-hint").innerText = "Please enter your username";
            document.getElementById("login-username-hint").style.color = "red";
            this.style.borderColor = "red";
        } else {
            document.getElementById("login-username-hint").innerText = "";
            this.style.borderColor = "";
        }
    });

// Validate login password input
    document.getElementById("login-password").addEventListener("blur", function() {
        if (this.value.trim() === "") {
            document.getElementById("login-password-hint").innerText = "Please enter your password";
            document.getElementById("login-password-hint").style.color = "red";
            this.style.borderColor = "red";
        } else {
            document.getElementById("login-password-hint").innerText = "";
            this.style.borderColor = "";
        }
    });


// Clear login error message on focus
    document.getElementById("login-username").addEventListener("focus", function() {
        document.getElementById("login-error-message").innerText = "";
        this.style.borderColor = "";
    });

    document.getElementById("login-password").addEventListener("focus", function() {
        document.getElementById("login-error-message").innerText = "";
        this.style.borderColor = "";
    });
});
    </script>
     



</body>
</html>
