<?php
session_start();
include '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Validate empty fields
    if (empty($email) || empty($password)) {
        echo "<script>alert('Please fill in all fields.');</script>";
    } else {
        // Check if student exists
        $sql = "SELECT * FROM students WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $student = $result->fetch_assoc();
            // Verify password
            if (password_verify($password, $student['password'])) {
                // Set session and redirect
                $_SESSION['user_id'] = $student['id'];
                $_SESSION['role'] = 'student';
                $_SESSION['email'] = $student['email'];
                header("Location: dashboard.php");
                exit();
            } else {
                echo "<script>alert('Invalid password. Please try again.');</script>";
            }
        } else {
            echo "<script>alert('No account found with this email. Please register.');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Login | UniPortal</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #3A86FF;
            --secondary: #8338EC;
            --accent: #FF006E;
            --dark: #1A1A2E;
            --light: #F8F9FA;
        }
        
        body {
            background: linear-gradient(-45deg, #1A1A2E, #16213E, #0F3460, #533483);
            background-size: 400% 400%;
            animation: gradient 15s ease infinite;
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            margin: 0;
            color: white;
        }
        
        @keyframes gradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        
        .glass-card {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-radius: 16px;
            border: 1px solid rgba(255, 255, 255, 0.15);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            overflow: hidden;
        }
        
        .login-container {
            max-width: 450px;
            width: 100%;
        }
        
        .form-control {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.25);
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            background: rgba(255, 255, 255, 0.2);
            border-color: var(--primary);
            box-shadow: 0 0 0 0.25rem rgba(58, 134, 255, 0.25);
            color: white;
        }
        
        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }
        
        .btn-login {
            background: linear-gradient(45deg, var(--primary), var(--secondary));
            border: none;
            border-radius: 8px;
            padding: 12px;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
            transition: all 0.3s ease;
            width: 100%;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(58, 134, 255, 0.5);
        }
        
        .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255, 255, 255, 0.7);
        }
        
        .input-with-icon {
            padding-left: 45px !important;
        }
        
        .forgot-link {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            font-size: 0.9rem;
            transition: all 0.2s ease;
        }
        
        .forgot-link:hover {
            color: white;
            text-decoration: underline;
        }
        
        .register-text {
            color: rgba(255, 255, 255, 0.8);
        }
        
        .register-link {
            color: var(--primary);
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
        }
        
        .register-link:hover {
            color: var(--accent);
            text-decoration: underline;
        }
        
        .divider {
            display: flex;
            align-items: center;
            margin: 20px 0;
            color: rgba(255, 255, 255, 0.5);
        }
        
        .divider::before, .divider::after {
            content: "";
            flex: 1;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .divider::before {
            margin-right: 15px;
        }
        
        .divider::after {
            margin-left: 15px;
        }
        
        .logo {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            background: linear-gradient(45deg, #3A86FF, #FFBE0B);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }
        
        .welcome-text {
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 2rem;
        }
        
        .form-check-input:checked {
            background-color: var(--primary);
            border-color: var(--primary);
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center min-vh-100 p-3">
        <div class="login-container glass-card p-4 p-md-5">
            <div class="text-center mb-4">
                <div class="logo">
                    <i class="fas fa-graduation-cap me-2"></i>Student
                </div>
                <p class="welcome-text">Welcome back! Please login to continue</p>
            </div>
            
            <form action="student_login.php" method="post">
                <!-- Email Field -->
                <div class="mb-4">
                    <label for="email" class="form-label mb-2">Email Address</label>
                    <div class="position-relative">
                        <i class="input-icon fas fa-envelope"></i>
                        <input type="email" class="form-control input-with-icon" id="email" name="email" placeholder="studentkdc@gmail.com" required>
                    </div>
                </div>

                <!-- Password Field -->
                <div class="mb-3">
                    <label for="password" class="form-label mb-2">Password</label>
                    <div class="position-relative">
                        <i class="input-icon fas fa-lock"></i>
                        <input type="password" class="form-control input-with-icon" id="password" name="password" placeholder="••••••••" required>
                    </div>
                    <div class="text-end mt-2">
                        <a href="forgot_password_student.php" class="forgot-link">Forgot Password?</a>
                    </div>
                </div>

                <!-- Remember Me Checkbox -->
                <div class="mb-4 form-check">
                    <input type="checkbox" class="form-check-input" id="remember">
                    <label class="form-check-label" for="remember">Remember me</label>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-login mb-4">SIGN IN</button>
                
                <div class="divider">or</div>
                
                <!-- Registration Link -->
                <p class="register-text text-center mb-0">Don't have an account? <a href="student_register.php" class="register-link">Register here</a></p>
            </form>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>