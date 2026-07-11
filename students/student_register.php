<?php
include '../config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $course = $_POST['course'];
    $phone_number = $_POST['phone_number'];
    $unique_id = $_POST['unique_id'];
    $gender = $_POST['gender'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $check_query = "SELECT * FROM students WHERE email = '$email' OR unique_id = '$unique_id'";
    $check_result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        echo "<script>alert('Email or Unique ID already exists! Please try again.');</script>";
    } else {
        $query = "INSERT INTO students (name, email, course, phone_number, unique_id, gender, password, created_at) VALUES ('$name', '$email', '$course', '$phone_number', '$unique_id', '$gender', '$password', NOW())";
        if (mysqli_query($conn, $query)) {
            echo "<script>alert('Registration successful! Please login.'); window.location.href='student_login.php';</script>";
        } else {
            echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #4e73df;
            --secondary: #2e59d9;
            --accent: #f6c23e;
            --light: #f8f9fc;
            --dark: #5a5c69;
        }
        
        body {
            background: linear-gradient(135deg, #f8f9fc 0%, #e3e6f0 100%);
            font-family: 'Nunito', -apple-system, BlinkMacSystemFont, sans-serif;
            min-height: 100vh;
        }
        
        .registration-card {
            border-radius: 0.5rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            border: none;
            overflow: hidden;
            background: white;
        }
        
        .card-header {
            background: linear-gradient(to right, var(--primary), var(--secondary));
            color: white;
            padding: 1.5rem;
            text-align: center;
            border-bottom: none;
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
        
        .btn-register {
            background: linear-gradient(to right, var(--primary), var(--secondary));
            border: none;
            border-radius: 0.35rem;
            padding: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.05rem;
            transition: all 0.3s;
            width: 100%;
        }
        
        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 0.5rem 1rem rgba(46, 89, 217, 0.2);
        }
        
        .form-label {
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 0.5rem;
        }
        
        .gender-option {
            border: 1px solid #d1d3e2;
            border-radius: 0.35rem;
            padding: 0.5rem 1rem;
            margin-right: 0.5rem;
            transition: all 0.3s;
        }
        
        .gender-option:hover {
            border-color: var(--primary);
        }
        
        .form-check-input:checked {
            background-color: var(--primary);
            border-color: var(--primary);
        }
        
        .brand-text {
            font-weight: 600;
            letter-spacing: 0.1rem;
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body class="d-flex justify-content-center align-items-center">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="registration-card">
                    <div class="card-header">
                        <h3 class="brand-text mb-1">
                            <i class="fas fa-user-graduate me-2"></i>Student Registration
                        </h3>
                        <p class="mb-0">Create your academic account</p>
                    </div>
                    <div class="card-body p-4 p-md-5">
                        <form action="student_register.php" method="post">
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label for="name" class="form-label">Full Name</label>
                                    <input type="text" class="form-control" id="name" name="name" placeholder="John Doe" required>
                                </div>

                                <div class="col-md-6 mb-4">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" placeholder="student@university.edu" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label for="course" class="form-label">Course</label>
                                    <input type="text" class="form-control" id="course" name="course" placeholder="Computer Science" required>
                                </div>

                                <div class="col-md-6 mb-4">
                                    <label for="phone_number" class="form-label">Phone Number</label>
                                    <input type="text" class="form-control" id="phone_number" name="phone_number" placeholder="+1234567890" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label for="unique_id" class="form-label">Unique ID (Roll Number)</label>
                                    <input type="text" class="form-control" id="unique_id" name="unique_id" placeholder="2023CS001" required>
                                </div>

                                <div class="col-md-6 mb-4">
                                    <label class="form-label">Gender</label>
                                    <div class="d-flex flex-wrap">
                                        <div class="form-check gender-option">
                                            <input class="form-check-input" type="radio" name="gender" id="male" value="Male" required>
                                            <label class="form-check-label" for="male">Male</label>
                                        </div>
                                        <div class="form-check gender-option">
                                            <input class="form-check-input" type="radio" name="gender" id="female" value="Female" required>
                                            <label class="form-check-label" for="female">Female</label>
                                        </div>
                                        <div class="form-check gender-option">
                                            <input class="form-check-input" type="radio" name="gender" id="other" value="Other" required>
                                            <label class="form-check-label" for="other">Other</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Create a strong password" required>
                            </div>

                            <button type="submit" class="btn btn-register mt-3">
                                <i class="fas fa-user-plus me-2"></i>Register Now
                            </button>
                        </form>
                    </div>
                </div>
                
                <div class="text-center mt-4">
                    <p>Already have an account? <a href="student_login.php" class="text-primary">Login here</a></p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>