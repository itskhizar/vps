<?php
include("include/classes/session.php");

$result = $database->clientdata($session->username);
// $username  = ($result['username']);
// echo $username;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup Page</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Optional: Add your custom styles here -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
        }

        .signup-container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            max-width: 400px;
            width: 100%;
            text-align: center;
            margin: auto;
            margin-top: 50px;
        }

        .signup-form input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .signup-form button {
            width: 100%;
            padding: 10px;
            background-color: #1877f2;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .login-links {
            margin-top: 15px;
        }
    </style>
</head>

<body>

    <?php
    if ($session->logged_in) {
        /// Content After Login
        echo "<script>location.href='dashboard.php'</script>";
    } else {
        /// Content without login
        ?>


        <div class="container signup-container">
            <h2>Sign Up</h2>
            <form class="signup-form" action="process.php" method="post">
                <input type="text" name="user" placeholder="Name" value="">
                <p><?php echo $form->error("user"); ?></p>
                <input type="email" name="email" placeholder="Email">
                <p><?php echo $form->error("email"); ?></p>
                <input type="password" name="pass" placeholder="Password">
                <p><?php echo $form->error("pass"); ?></p>
                <input type="submit" name="subjoin" class="btn btn-primary">
            </form>
            <div class="login-links">
                <p>Already have an account? <a href="index.php">LogIn</a></p>
                <p><a href="forgot_password.php">Forgot Password?</a></p>
            </div>
        </div>

        <!-- Bootstrap JS (Optional: If you need Bootstrap JavaScript features) -->
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    </body>

    </html>
    <?php
    }
    ?>