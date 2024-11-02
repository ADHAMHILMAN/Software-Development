<?php
include('db.php');
session_start();
$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Check if the account is verified
        if ($user['is_verified'] == 1) {
            // Verify password
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['name'] = $user['name'];

                if ($user['role'] === 'admin') {
                header("Location: admin_dashboard.php"); // Redirect to admin dashboard
            } elseif ($user['role'] === 'resident') {
                header("Location: dashboard.php"); // Redirect to resident dashboard
            } else {
                header("Location: dashboard.php"); // Default dashboard for other roles, if any
            }
            exit(); // Make sure to exit after redirection

               // header("Location: dashboard.php");
            } else {
                $message = "Invalid password!";
            }
        } else {
            $message = "Please verify your email address before logging in.";
        }
    } else {
        $message = "No user found with that email!";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet"> <!-- Include Poppins font -->
    <title>Login</title>
    <style>
        /* Ensure full height for the body and container */
        body, html { height: 100%; margin: 0; font-family: 'Poppins', sans-serif; } /* Apply Poppins font */
        .container { display: flex; width: 100%; height: 100vh; }

        /* Left and right side styling */
        .left-side, .right-side {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Left side styling */
        .left-side { background-color: #f8f9fa; padding: 20px; }
        .form-container {
            background: transparent; /* Make the background transparent */
            padding: 0; /* Remove padding */
            border-radius: 10px; /* Keep the border radius */
            max-width: 350px;
            width: 100%;
        }
        .login-title { 
            text-align: center; 
            margin-bottom: 1px; /* Reduce margin to bring closer */
            font-size: 48px; /* Larger font size */
            font-weight: bold; /* Bold text */
        }
        .welcome-text { 
            text-align: center; 
            margin-bottom: 60px; /* Maintain space below the welcome text */
            font-size: 16px; /* Set font size for welcome message */
            color: gray;
        }
        .alert { text-align: center; }

        /* Right side background image styling */
        .right-side {
            background-image: url('flat3.jpg'); /* Change this to your desired image */
            background-repeat: no-repeat;
            background-position: center;
            background-size: cover;
            
        }
        .register-link { 
            text-align: left; 
            margin-top: 15px;  
            margin-bottom:2px;
            font-size: 12px; 
        }
        
        /* Custom button styles */
      .btn-custom {
    background-color: black; /* Black background */
    color: white; /* White text */
    border-radius: 25px; /* Increase border-radius for rounded corners */
    padding: 10px 20px; /* Adjust padding for a balanced appearance */
}
.btn-custom:hover {
    background-color: #333; /* Darker shade on hover */
    color: white; /* Keep text white on hover */
}
/* Taskbar Styling */
.navbar {
    background-color: white;
    padding: 10px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    color: black;
    border-bottom: 1px solid #000000;
}

.navbar-left, .navbar-right {
    display: flex;
    align-items: center;
}

#languageSelect {
    background-color: #FFFFFF;
    color: black;
    border: none;
    padding: 5px;
    margin-right: 10px;
    border-radius: 5px;
}

.btn-light {
    color: black;
    background-color: white;
    border-radius: 10px;
    padding: 5px 15px;
    border: 2px solid #000000;
}

    </style>
</head>
    <!-- Top Taskbar -->
    <nav class="navbar">
        <div class="container-fluid">
            <div class="navbar-left">

            </div>
            <div class="navbar-right">
                 <!-- Language Dropdown -->
                <select id="languageSelect" class="form-select">
                <option value="en">English</option>
                <option value="my">Malay</option>
                <!-- Add more languages as needed -->
                </select>
                <!-- Register Button -->
                <a href="register.php" class="btn btn-light">Register</a>
            </div>
        </div>
    </nav>

<body>
    <div class="container">
        <!-- Left Side with Login Form -->
        <div class="left-side">
            <div class="form-container">
                <div class="login-title" id="loginTitle">Login</div>
                <div class="welcome-text" id="welcomeText">Welcome to Flat Jubli Perak Community!</div>
                <form method="POST" action="">
                    <div class="form-group">
                        <input type="email" name="email" class="form-control" placeholder="Email Address" required>
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" class="form-control" placeholder="Password" required>
                    </div>
                    <div class="register-link" id="registerLink">
                    Don't have an account? <a href="register.php">Register now!</a>
                    </div>
                    <button type="submit" class="btn btn-custom btn-block" id="loginButton">Login</button> <!-- Custom button class -->
                    <?php if ($message): ?>
                        <div class="alert alert-danger mt-3"><?php echo $message; ?></div>
                    <?php endif; ?>
                </form>
                
            </div>
        </div>

        <!-- Right Side with Full Background Image -->
        <div class="right-side"></div>
    </div>
    <script src="translationsLogin.js"></script>
<script>
    document.getElementById("languageSelect").addEventListener("change", function() {
        const selectedLanguage = this.value;
        updateLanguage(selectedLanguage);
    });
</script>
</body>
</html>