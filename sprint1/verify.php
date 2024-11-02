<?php
include('db.php');
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: register.php");
    exit();
}

$message = '';
$email = $_SESSION['email'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $entered_code = $_POST['verification_code'];

    // Check if the entered code matches the one in the database
    $stmt = $conn->prepare("SELECT verification_code FROM users WHERE email = ? AND is_verified = 0");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($verification_code);
    $stmt->fetch();
    $stmt->close();

    if ($entered_code == $verification_code) {
        // Update user as verified
        $stmt = $conn->prepare("UPDATE users SET is_verified = 1, verification_code = NULL WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->close();

        // Clear session email and redirect to login
        unset($_SESSION['email']);
        header("Location: login.php");
        exit();
    } else {
        $message = "Incorrect verification code. Please try again.";
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
    <title>Verify Email</title>
    <style>
        body, html {
            height: 100%;
            margin: 0;
            font-family: 'Poppins', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f8f9fa;
        }
        .container {
            width: 100%;
            max-width: 400px;
            padding: 20px;
        }
        .verify-container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .verify-title {
            font-size: 32px;
            font-weight: bold;
            margin-bottom: 20px;
            color: #333;
        }
        .form-group input {
            height: 45px;
            font-size: 16px;
            border-radius: 25px;
        }
        .btn-custom {
            background-color: black;
            color: white;
            border-radius: 25px;
            font-size: 16px;
            padding: 10px 20px;
        }
        .btn-custom:hover {
            background-color: #333;
            color: white;
        }
        .alert {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="verify-container">
            <div class="verify-title">Verify Email</div>
            <p>Please enter the verification code sent to your email.</p>
            <form method="POST" action="">
                <div class="form-group">
                    <input type="text" name="verification_code" class="form-control" placeholder="Verification Code" required>
                </div>
                <button type="submit" class="btn btn-custom btn-block">Verify</button>
                <?php if ($message): ?>
                    <div class="alert alert-danger mt-3"><?php echo $message; ?></div>
                <?php endif; ?>
            </form>
        </div>
    </div>
</body>
</html>
