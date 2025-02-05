<?php
include 'user_authentication_functions.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reset-password'])) {

    $email = isset($_POST['email']) ? $_POST['email'] : null;

    // Check if email exists in the database
    if ($email) {
        $user = getUserByEmail($email);

        if (count($user) > 0) {
            // Generate a unique verification code
            $verificationCode = generateVerificationCode();

            // Store the verification code in the database
            storeVerificationCode($user['id'], $verificationCode);

            // Send the password reset email
            $resetLink = "http://yourdomain.com/changepassword.php?v=$verificationCode";
            $subject = "Password Reset Request";
            $message = "Hello, \n\nWe received a request to reset your password. Please click the link below to reset your password:\n\n$resetLink\n\nIf you didn't request a password reset, please ignore this email.";
            $headers = "From: no-reply@yourdomain.com";

            if (mail($email, $subject, $message, $headers)) {
                echo "A password reset link has been sent to your email. Please check your inbox.";
            } else {
                echo "Failed to send password reset email. Please try again.";
            }
        } else {
            echo "No account found with that email address.";
        }
    } else {
        echo "Please enter a valid email address.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <link rel="stylesheet" href="forms.css"/>
</head>
<body>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <ul class="form-style-1">
            <h3>Reset your password</h3>

            <!-- Email -->
            <li>
                <label>Enter your email:</label>
                <input name="email" type="email" autocomplete="off" required/>
            </li>
            <br>

            <!-- Submit button -->
            <input type="submit" name="reset-password" value="Send Password Reset Link"/>
            <br>

            <a href="login.php">Back to login</a>
        </ul>
    </form>
</body>
</html>
