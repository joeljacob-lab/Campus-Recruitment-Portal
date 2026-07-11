<?php
ob_start();
include '../config/db.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Fetch recruiter details from the database
    $query = "SELECT * FROM recruiters WHERE email='$email'";
    $result = mysqli_query($conn, $query);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        
        // Verify hashed password
        if (password_verify($password, $row['password'])) {
            $_SESSION['recruiter_id'] = $row['id'];
            $_SESSION['recruiter_name'] = $row['name'];
            $_SESSION['recruiter_email'] = $row['email'];
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['role'] = 'recruiter';
            
            header("Location: /campus-recruitment-portal/recruiters/dashboard.php");
            exit();
        } else {
            $error = "Invalid email or password!";
        }
    } else {
        $error = "Invalid email or password!";
    }
}
ob_end_flush();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recruiter Login</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        .login-container {
            display: flex;
            background-color: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .form-container {
            flex: 1;
            padding: 40px;
            display: flex;
            flex-direction: column;
        }

        .form-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .form-header h2 {
            font-weight: 500;
            color: #333;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #333;
            font-weight: 600;
        }

        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #eee;
            border-radius: 25px;
            font-size: 14px;
            background-color: #f9f9f9;
            outline: none;
            transition: border-color 0.3s;
        }

        .form-group input:focus {
            border-color: rgb(20, 75, 193);
        }

        .options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .checkbox-container {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .checkbox-container input {
            accent-color: rgb(20, 75, 193);
        }

        .checkbox-container label {
            font-size: 14px;
            color: #666;
        }

        .forgot-password {
            font-size: 14px;
            color: #666;
            text-decoration: none;
        }

        .forgot-password:hover {
            color: rgb(20, 75, 193);
        }

        .signin-btn {
            background-color: rgb(20, 75, 193);
            color: white;
            border: none;
            border-radius: 25px;
            padding: 12px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
            width: 100%;
        }

        .signin-btn:hover {
            background-color: rgb(23, 2, 70);
        }

        .welcome-container {
            flex: 1;
            background-color: rgb(20, 75, 193);
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 40px;
            text-align: center;
        }

        .welcome-container h2 {
            margin-bottom: 20px;
            font-size: 28px;
            font-weight: 500;
        }

        .welcome-container p {
            margin-bottom: 30px;
            font-size: 16px;
        }

        .signup-btn {
            background-color: transparent;
            color: white;
            border: 2px solid white;
            border-radius: 25px;
            padding: 10px 25px;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .signup-btn:hover {
            background-color: white;
            color: rgb(14, 6, 128);
        }

        .error-message {
            color: #e74c3c;
            font-size: 14px;
            margin-bottom: 15px;
            text-align: center;
        }

        @media (max-width: 768px) {
            .login-container {
                flex-direction: column;
            }
            
            .welcome-container {
                order: -1;
                padding: 30px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-container">
            <div class="form-container">
                <div class="form-header">
                    <h2>Recruiter Login</h2>
                </div>
                
                <?php if(isset($error)): ?>
                    <div class="error-message"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="email">EMAIL</label>
                        <input type="email" id="email" name="email" placeholder="Email" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="password">PASSWORD</label>
                        <input type="password" id="password" name="password" placeholder="Password" required>
                    </div>
                    
                    <button type="submit" class="signin-btn">Sign In</button>
                    
                    <div class="options">
                        <div class="checkbox-container">
                            <input type="checkbox" id="remember" name="remember">
                            <label for="remember">Remember Me</label>
                        </div>
                        <a href="forgot_password_recruiter.php" class="forgot-password">Forgot Password?</a>
                    </div>
                </form>
            </div>
            
            <div class="welcome-container">
                <h2>Welcome to login</h2>
                <p>Don't have an account?</p>
                <button class="signup-btn" onclick="window.location.href='recruiter_register.php';">Sign Up</button>
            </div>
        </div>
    </div>
</body>
</html>