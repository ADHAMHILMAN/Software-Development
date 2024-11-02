<?php
include('db.php');
require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();

// Check if the user is an admin
if ($_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $role = $_POST['role'];
    $access_code = rand(100000, 999999); // Generate a 6-digit access code

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid email format!";
    } else {
        // Insert new user into the database (is_verified = 0 by default)
        $stmt = $conn->prepare("INSERT INTO users (email, role, verification_code, is_verified) VALUES (?, ?, ?, 0)");
        $stmt->bind_param("ssi", $email, $role, $access_code);

        if ($stmt->execute()) {
            // Send access code to the user's email
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'adhamhilman26@gmail.com';
                $mail->Password = 'wnds okgp avbd eufm';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                $mail->setFrom('adhamhilman26@gmail.com', 'Flat Jubli Perak System');
                $mail->addAddress($email);

                $mail->isHTML(true);
                $mail->Subject = 'Access Code for Registration';
                $mail->Body = "Your access code is: <strong>$access_code</strong>";

                $mail->send();
                $message = "Access code sent to $email successfully!";
            } catch (Exception $e) {
                $message = "Failed to send access code. Error: {$mail->ErrorInfo}";
            }
        } else {
            $message = "Error: " . $stmt->error;
        }
        $stmt->close();
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
    <title>Add New User</title>
    <style>
        body {
            background-color: #f4f4f9;
        }
        .container {
            margin-top: 50px;
        }
        .alert {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Add New User</h2>
        <form method="POST" action="">
            <div class="form-group">
                <input type="email" name="email" class="form-control" placeholder="User Email" required>
            </div>
            <div class="form-group">
                <select name="role" class="form-control" required>
                    <option value="" disabled selected>Select Role</option>
                    <option value="resident">Resident</option>
                    <option value="maintenance_staff">Maintenance Staff</option>
                    <option value="admin">Admin</option>
                    <!-- Add other roles as needed -->
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Send Access Code</button>
            <?php if ($message): ?>
                <div class="alert alert-success mt-3"><?php echo $message; ?></div>
            <?php endif; ?>
        </form>
    </div>
</body>
</html>
