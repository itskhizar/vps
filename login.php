<?php 
    include("include/classes/session.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Filenod Academy</title>
    <link rel="icon" href="images/favicon.webp" type="image/x-icon">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="assets/img/favicon-filenod.png" rel="icon">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #e8eef5 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 15px;
        }

        .login-wrapper {
            width: 100%;
            max-width: 420px;
            margin: 0 auto;
        }

        .login-container {
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
            padding: 32px 32px 28px;
            position: relative;
            overflow: hidden;
        }

        .login-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #1877f2 0%, #0d5dbf 100%);
        }

        .logo-container {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo-container img {
            width: 200px;
            height: auto;
            transition: transform 0.3s ease;
        }

        .logo-container img:hover {
            transform: scale(1.05);
        }

        .login-header {
            text-align: center;
            margin-bottom: 24px;
        }

        .login-header h1 {
            font-size: 20px;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 4px;
        }

        .login-header p {
            font-size: 13px;
            color: #6c757d;
            margin: 0;
        }

        .login-form {
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 18px;
            position: relative;
        }

        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #344054;
            margin-bottom: 6px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
            font-size: 14px;
            z-index: 1;
        }

        .toggle-password {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
            font-size: 14px;
            cursor: pointer;
            z-index: 1;
            transition: color 0.3s ease;
            padding: 5px;
        }

        .toggle-password:hover {
            color: #1877f2;
        }

        .form-control {
            width: 100%;
            padding: 11px 14px 11px 40px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 14px;
            color: #1a1a1a;
            transition: all 0.3s ease;
            background-color: #f8fafc;
        }

        .form-control.with-toggle {
            padding-right: 40px;
        }

        .form-control:focus {
            outline: none;
            border-color: #1877f2;
            background-color: #ffffff;
            box-shadow: 0 0 0 3px rgba(24, 119, 242, 0.1);
        }

        .form-control::placeholder {
            color: #94a3b8;
        }

        .error-message {
            color: #dc3545;
            font-size: 12px;
            margin-top: 4px;
            display: block;
            font-weight: 500;
        }

        .forgot-password {
            text-align: right;
            margin-top: -10px;
            margin-bottom: 18px;
        }

        .forgot-password a {
            font-size: 12px;
            color: #1877f2;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .forgot-password a:hover {
            color: #0d5dbf;
        }

        .btn-login {
            width: 100%;
            padding: 12px 20px;
            background: linear-gradient(135deg, #1877f2 0%, #0d5dbf 100%);
            color: #ffffff;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(24, 119, 242, 0.3);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-login:hover {
            background: linear-gradient(135deg, #0d5dbf 0%, #1877f2 100%);
            box-shadow: 0 6px 16px rgba(24, 119, 242, 0.4);
            transform: translateY(-2px);
        }

        .btn-login:active {
            transform: translateY(0);
            box-shadow: 0 2px 8px rgba(24, 119, 242, 0.3);
        }

        .divider {
            display: flex;
            align-items: center;
            margin: 20px 0 18px;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e2e8f0;
        }

        .divider span {
            padding: 0 12px;
            font-size: 12px;
            color: #94a3b8;
            font-weight: 500;
            text-transform: uppercase;
        }

        .login-links {
            text-align: center;
        }

        .login-links a {
            color: #1877f2;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-block;
            position: relative;
        }

        .login-links a::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -2px;
            left: 50%;
            background-color: #1877f2;
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }

        .login-links a:hover {
            color: #0d5dbf;
        }

        .login-links a:hover::after {
            width: 100%;
        }

        .login-links p {
            margin: 0;
            color: #6c757d;
            font-size: 13px;
        }

        .footer-text {
            text-align: center;
            margin-top: 20px;
            padding-top: 18px;
            border-top: 1px solid #e2e8f0;
        }

        .footer-text p {
            color: #94a3b8;
            font-size: 11px;
            margin: 0;
        }

        /* Loading animation */
        .btn-login.loading {
            position: relative;
            color: transparent;
            pointer-events: none;
        }

        .btn-login.loading::after {
            content: '';
            position: absolute;
            width: 18px;
            height: 18px;
            top: 50%;
            left: 50%;
            margin-left: -9px;
            margin-top: -9px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-top-color: #ffffff;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Responsive Design */
        @media (max-width: 576px) {
            .login-container {
                padding: 28px 24px 24px;
            }

            .login-header h1 {
                font-size: 20px;
            }

            .logo-container img {
                width: 200px;
            }
        }

        @media (max-width: 400px) {
            .login-container {
                padding: 24px 20px 20px;
            }

            .form-control {
                padding: 10px 12px 10px 38px;
                font-size: 13px;
            }

            .btn-login {
                padding: 11px 18px;
                font-size: 14px;
            }
        }

        @media (max-height: 700px) {
            .login-container {
                padding: 24px 32px 20px;
            }
            
            .logo-container {
                margin-bottom: 16px;
            }
            
            .logo-container img {
                width: 200px;
            }
            
            .login-header {
                margin-bottom: 20px;
            }
            
            .form-group {
                margin-bottom: 16px;
            }
        }

        /* Accessibility improvements */
        .form-control:focus-visible {
            outline: 2px solid #1877f2;
            outline-offset: 2px;
        }

        /* Animation for page load */
       /* @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }*/

        .login-container {
            animation: fadeInUp 0.5s ease-out;
        }
    </style>
</head>
<body>
    <?php
    if ($session->logged_in){
        $result = $database->clientdata($session->username);
        $username  = ($result['username']);
        echo "<script>location.href='dashboard.php';</script>";
    ?>
    <?php 
    } else {
    ?>
    
    <div class="login-wrapper">
        <div class="login-container">
            <!-- Logo -->
            <div class="logo-container">
                <img src="images/logo.png" alt="Filenod Academy Logo">
            </div>

            <!-- Header -->
            <div class="login-header">
                <h1>Welcome Back</h1>
                <p>Login to continue to Filenod Academy</p>
            </div>

            <!-- Login Form -->
            <form class="login-form" action="process.php" method="post" id="loginForm">
                <!-- Username Field -->
                <div class="form-group">
                    <label for="username">Username</label>
                    <div class="input-wrapper">
                        <i class="fas fa-user input-icon"></i>
                        <input 
                            type="text" 
                            class="form-control" 
                            id="username"
                            name="user" 
                            placeholder="Enter your username" 
                            maxlength="30"
                            value="<?php if(isset($form)) echo $form->value("user"); ?>"
                            required
                            autocomplete="username"
                        >
                    </div>
                    <?php if(isset($form) && $form->error("user")): ?>
                        <span class="error-message">
                            <i class="fas fa-exclamation-circle"></i> <?php echo $form->error("user"); ?>
                        </span>
                    <?php endif; ?>
                </div>

                <!-- Password Field -->
                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-wrapper">
                        <i class="fas fa-lock input-icon"></i>
                        <input 
                            type="password" 
                            class="form-control with-toggle" 
                            id="password"
                            name="pass" 
                            placeholder="Enter your password" 
                            maxlength="30"
                            value="<?php if(isset($form)) echo $form->value("pass"); ?>"
                            required
                            autocomplete="current-password"
                        >
                        <i class="fas fa-eye toggle-password" id="togglePassword"></i>
                    </div>
                    <?php if(isset($form) && $form->error("pass")): ?>
                        <span class="error-message">
                            <i class="fas fa-exclamation-circle"></i> <?php echo $form->error("pass"); ?>
                        </span>
                    <?php endif; ?>
                </div>

                <!-- Forgot Password Link -->
                <div class="forgot-password">
                    <a href="forgot_password.php">Forgot Password?</a>
                </div>

                <!-- Login Button -->
                <button type="submit" name="sublogin" class="btn-login" id="loginBtn">
                    Login
                </button>
            </form>

            <!-- Divider -->
            <div class="divider">
                <span>or</span>
            </div>

            <!-- Sign Up Link -->
            <div class="login-links">
                <p>Ready to join? <a href="admission.php" class="text-purple-600 hover:underline">Start Your Admission</a></p>
            </div>


            <!-- Footer -->
            <div class="footer-text">
                <p>&copy; <?php echo date('Y'); ?> Filenod Academy. All rights reserved.</p>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <!-- jQuery (latest, for plugins that still require it) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <!-- Bootstrap 5 JS Bundle (includes Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

    <!-- Custom JavaScript -->
    <script>
        // Password visibility toggle
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');

        togglePassword.addEventListener('click', function() {
            // Toggle password visibility
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            // Toggle eye icon
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });

        // Clear error messages on input
        document.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('input', function() {
                const errorMsg = this.closest('.form-group').querySelector('.error-message');
                if (errorMsg) {
                    errorMsg.style.display = 'none';
                }
                this.style.borderColor = '#e2e8f0';
            });
        });

        // Highlight fields with errors on page load
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.error-message').forEach(error => {
                if (error.textContent.trim()) {
                    const input = error.closest('.form-group').querySelector('.form-control');
                    if (input) {
                        input.style.borderColor = '#dc3545';
                        input.focus();
                    }
                }
            });
        });
    </script>

    <?php 
    }
    ?>
</body>
</html>