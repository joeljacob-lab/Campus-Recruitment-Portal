<?php
include '../config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $phone = $_POST['phone'];
    $company_name = $_POST['company_name'];
    $company_address = $_POST['company_address'];
    $created_at = date("Y-m-d H:i:s");

    $query = "INSERT INTO recruiters (name, email, password, phone, company_name, company_address, created_at) 
              VALUES ('$name', '$email', '$password', '$phone', '$company_name', '$company_address', '$created_at')";

    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Registration successful!'); window.location.href='recruiter_login.php';</script>";
    } else {
        echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recruiter Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #3a7bd5;
            --primary-light: #00d2ff;
            --dark: #2c3e50;
            --light: #f8f9fa;
        }
        
        body {
            background: linear-gradient(135deg, #e0f7fa 0%, #b2ebf2 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
            padding: 20px;
        }
        
        .registration-box {
            width: 100%;
            max-width: 600px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            position: relative;
        }
        
        .form-header {
            background: linear-gradient(to right, var(--primary), var(--primary-light));
            color: white;
            padding: 25px;
            text-align: center;
        }
        
        .form-body {
            padding: 30px;
        }
        
        .form-title {
            font-weight: 600;
            margin-bottom: 5px;
        }
        
        .form-subtitle {
            font-size: 0.9rem;
            opacity: 0.9;
        }
        
        .form-control {
            border-radius: 8px;
            padding: 12px 15px;
            border: 1px solid #e0e0e0;
            transition: all 0.3s;
        }
        
        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(58, 123, 213, 0.1);
        }
        
        .btn-register {
            background: linear-gradient(to right, var(--primary), var(--primary-light));
            border: none;
            border-radius: 8px;
            padding: 12px;
            font-weight: 600;
            color: white;
            width: 100%;
            transition: all 0.3s;
        }
        
        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(58, 123, 213, 0.3);
        }
        
        .form-label {
            font-weight: 500;
            color: var(--dark);
            margin-bottom: 8px;
        }
        
        .login-link {
            text-align: center;
            margin-top: 20px;
            color: #666;
        }
        
        .login-link a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
        }
        
        .input-icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #aaa;
        }
        
        @media (max-width: 768px) {
            .form-body {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="registration-box">
        <div class="form-header">
            <h3 class="form-title"><i class="fas fa-user-tie me-2"></i>Recruiter Registration</h3>
            <p class="form-subtitle">Join our professional network</p>
        </div>
        
        <div class="form-body">
            <form action="recruiter_register.php" method="post">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="John Doe" required>
                    </div>
                    
                    <div class="col-md-6">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="your@email.com" required>
                    </div>
                    
                    <div class="col-md-6">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="••••••••" required>
                    </div>
                    
                    <div class="col-md-6">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="text" class="form-control" id="phone" name="phone" placeholder="+1 (___) ___-____" required>
                    </div>
                    
                    <div class="col-12">
                        <label for="company_name" class="form-label">Company Name</label>
                        <input type="text" class="form-control" id="company_name" name="company_name" placeholder="Company Inc." required>
                    </div>
                    
                    <div class="col-12">
                        <label for="company_address" class="form-label">Company Address</label>
                        <textarea class="form-control" id="company_address" name="company_address" rows="3" placeholder="123 Business Ave, City" required></textarea>
                    </div>
                    
                    <div class="col-12 mt-3">
                        <button type="submit" class="btn btn-register">
                            <i class="fas fa-user-plus me-2"></i>Register Account
                        </button>
                    </div>
                </div>
            </form>
            
            <div class="login-link">
                Already have an account? <a href="recruiter_login.php">Sign in</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>