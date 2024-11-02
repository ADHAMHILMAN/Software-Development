<?php
include('db.php');
session_start();
$message = '';
$success_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $access_code = $_POST['access_code'];

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid email format!";
    } else {
        // Check if the access code exists in the database and retrieve the user role
        $stmt = $conn->prepare("SELECT role FROM users WHERE email = ? AND verification_code = ? AND is_verified = 0");
        $stmt->bind_param("ss", $email, $access_code);
        $stmt->execute();
        $stmt->bind_result($role);
        $stmt->fetch();
        $stmt->close();

        if ($role) {
            // If access code is valid, register the user and mark them as verified
            $stmt = $conn->prepare("UPDATE users SET name = ?, password = ?, is_verified = 1 WHERE email = ?");
            $stmt->bind_param("sss", $name, $password, $email);
            
            if ($stmt->execute()) {
                $success_message = "Registration successful! You can now log in.";
                //header("Location: login.php");
            } else {
                $message = "Error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $message = "Invalid access code or email!";
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <title>Register</title>
    <style>
        /* Ensure full height for the body and container */
        body, html { height: 100%; margin: 0; font-family: 'Poppins', sans-serif; }
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
            background: transparent;
            padding: 0;
            border-radius: 10px;
            max-width: 350px;
            width: 100%;
        }
        .register-title { 
            text-align: center; 
            margin-bottom: 1px; 
            font-size: 48px; 
            font-weight: bold; 
        }
        .welcome-text { 
            text-align: center; 
            margin-bottom: 60px; 
            font-size: 16px; 
            color: gray;
        }
        .alert { text-align: center; }

        /* Right side background image styling */
        .right-side {
            background-image: url('flat3.jpg');
            background-repeat: no-repeat;
            background-position: center;
            background-size: cover;
        }

        /* Custom button styles */
        .btn-custom {
            background-color: black;
            color: white;
            border-radius: 25px;
            padding: 10px 20px;
        }
        .btn-custom:hover {
            background-color: #333;
            color: white;
        }
        .login-link { 
            text-align: left; 
            margin-top: 15px; 
            font-size: 12px; 
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
<body>
    <!-- Top Taskbar -->
    <nav class="navbar">
        <div class="container-fluid">
            <div class="navbar-left"></div>
            <div class="navbar-right">
                <!-- Language Dropdown -->
                <select id="languageSelect" class="form-select">
                    <option value="en">English</option>
                    <option value="my">Malay</option>
                </select>
                <!-- Login Button -->
                <a href="login.php" class="btn btn-light">Login</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <!-- Left Side with Registration Form -->
        <div class="left-side">
            <div class="form-container">
                <div class="register-title" id="registerTitle">Register</div>
                <div class="welcome-text" id="welcomeText">Join the Flat Jubli Perak Community!</div>
                <form method="POST" action="">
                    <div class="form-group">
                        <input type="text" name="name" class="form-control" placeholder="Fullname" required>
                    </div>
                    <div class="form-group">
                        <input type="email" name="email" class="form-control" placeholder="Email" required>
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" class="form-control" placeholder="Password" required>
                    </div>
                    <div class="form-group">
                        <input type="text" name="access_code" class="form-control" placeholder="Access Code" required>
                    </div>
                    <div class="login-link" id="loginLink">
                        <p>Already have an account? <a href="login.php">Login here</a></p>
                    </div>
                    <button type="submit" class="btn btn-custom btn-block" id="registerButton">Register</button>
                    <?php if (isset($message) && $message): ?>
                        <div class="alert alert-danger mt-3"><?php echo $message; ?></div>
                    <?php endif; ?>
                    <?php if ($success_message): ?>
                        <div class="alert alert-success mt-3"><?php echo $success_message; ?></div>
                    <?php endif; ?>
                </form>
            </div>
        </div>

        <!-- Right Side with Full Background Image -->
        <div class="right-side"></div>
    </div>
    <script src="translationsRegister.js"></script>
<script>
    document.getElementById("languageSelect").addEventListener("change", function() {
        const selectedLanguage = this.value;
        updateLanguage(selectedLanguage);
    });
</script>
</body>
</html>
