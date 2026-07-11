<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../config/db.php'; // Make sure this path is correct

// Check DB connection
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);

    // Prepared statement to prevent SQL injection
    $query = "SELECT * FROM recruiters WHERE username = ? AND email = ?";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
    
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // If match found
    if ($result->num_rows > 0) {
        // Redirect using JavaScript to avoid header issues
        echo "<script>window.location.href='reset_password_recruiter.php?username=" . urlencode($username) . "';</script>";
        exit();
    } else {
        $error = "Invalid username or email!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password - Recruiter</title>
    <link rel="stylesheet" href="../assets/style.css"> <!-- Optional -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="col-md-6 offset-md-3">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Recruiter - Forgot Password</h4>
                </div>
                <div class="card-body">
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger"><?= $error; ?></div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label for="username" class="form-label">Enter Username</label>
                            <input type="text" name="username" class="form-control" required />
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Enter Registered Email</label>
                            <input type="email" name="email" class="form-control" required />
                        </div>

                        <button type="submit" class="btn btn-primary">Verify</button>
                        <a href="recruiter_login.php" class="btn btn-secondary float-end">Back to Login</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
