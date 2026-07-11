<?php
session_start();
include '../config/db.php';

if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $query = "SELECT * FROM admin WHERE email = '$email'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        if (password_verify($password, $row['password'])) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_name'] = $row['admin_name'];
            $_SESSION['admin_id'] = $row['id'];
            $_SESSION['role'] = 'admin';
            header('Location: dashboard.php');
            exit();
        } else {
            $error = "Invalid credentials";
        }
    } else {
        $error = "Account not found";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Portal | Secure Access</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #4e73df;
            --primary-dark: #2e59d9;
            --dark: #5a5c69;
            --light: #f8f9fc;
        }
        
        body {
            background: linear-gradient(135deg, #f8f9fc 0%, #e3e6f0 100%);
            font-family: 'Nunito', -apple-system, BlinkMacSystemFont, sans-serif;
            min-height: 100vh;
        }
        
        .login-card {
            border-radius: 0.5rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            border: none;
            overflow: hidden;
        }
        
        .card-header {
            background: linear-gradient(to right, var(--primary), var(--primary-dark));
            color: white;
            text-align: center;
            padding: 1.5rem;
            border-bottom: none;
        }
        
        .card-body {
            padding: 2rem;
        }
        
        .form-control {
            border-radius: 0.35rem;
            padding: 0.75rem 1rem;
            border: 1px solid #d1d3e2;
            transition: all 0.3s;
        }
        
        .form-control:focus {
            border-color: #bac8f3;
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
        }
        
        .btn-login {
            background: linear-gradient(to right, var(--primary), var(--primary-dark));
            border: none;
            border-radius: 0.35rem;
            padding: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.05rem;
            transition: all 0.3s;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 0.5rem 1rem rgba(46, 89, 217, 0.2);
        }
        
        .input-icon {
            position: absolute;
            right: 10px;
            top: 70%;
            transform: translateY(-50%);
            color: #d1d3e2;
        }
        
        .alert-danger {
            border-radius: 0.35rem;
            background-color: rgba(231, 74, 59, 0.1);
            border-color: rgba(231, 74, 59, 0.2);
            color: #e74a3b;
        }
        
        .brand-text {
            font-weight: 300;
            letter-spacing: 0.1rem;
            text-transform: uppercase;
        }
        
        .form-label {
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 0.5rem;
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body class="d-flex justify-content-center align-items-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-5 col-lg-6 col-md-8">
                <div class="login-card">
                    <div class="card-header">
                        <h4 class="brand-text mb-1">
                            <i class="fas fa-fingerprint mr-2"></i>Admin Portal
                        </h4>
                        <small>Secure authentication required</small>
                    </div>
                    <div class="card-body">
                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger text-center mb-4 py-2">
                                <i class="fas fa-exclamation-circle me-2"></i><?php echo $error; ?>
                            </div>
                        <?php endif; ?>
                        
                        <form method="post" action="">
                            <div class="mb-4 position-relative">
                                <label for="email" class="form-label">Admin Email</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="admin@example.com" required>
                                <i class="input-icon fas fa-user-shield"></i>
                            </div>
                            
                            <div class="mb-4 position-relative">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="••••••••" required>
                                <i class="input-icon fas fa-key"></i>
                            </div>
                            
                            <div class="form-group">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="remember">
                                    <label class="form-check-label" for="remember">
                                        Remember Device
                                    </label>
                                </div>
                            </div>
                            
                            <button type="submit" name="login" class="btn btn-login btn-block mt-4">
                                <i class="fas fa-sign-in-alt mr-2"></i>Login
                            </button>
                        </form>
                    </div>
                </div>
                
                <div class="text-center mt-4">
                    <a href="forgot_password_admin.php" class="text-primary">
                        <i class="fas fa-question-circle mr-1"></i>Forgot Password?
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>