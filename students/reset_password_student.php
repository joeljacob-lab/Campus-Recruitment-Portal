<?php
session_start();
include '../config/db.php'; // Make sure the path is correct

// Check DB connection
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Initialize variables
$success = $error = "";
$username = isset($_GET['username']) ? trim($_GET['username']) : '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username']; // Hidden field
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Validate input
    if ($new_password !== $confirm_password) {
        $error = "Passwords do not match!";
    } elseif (strlen($new_password) < 6) {
        $error = "Password should be at least 6 characters!";
    } else {
        // Hash new password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // Update in DB
        $update_query = "UPDATE students SET password = ? WHERE username = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("ss", $hashed_password, $username);
        if ($stmt->execute()) {
            $success = "Password updated successfully! <a href='student_login.php'>Click here to login</a>";
        } else {
            $error = "Failed to update password.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password - Student</title>
    <link rel="stylesheet" href="../assets/style.css"> <!-- Optional -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="col-md-6 offset-md-3">
            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">Reset Student Password</h4>
                </div>
                <div class="card-body">
                    <?php if ($success): ?>
                        <div class="alert alert-success"><?= $success; ?></div>
                    <?php elseif ($error): ?>
                        <div class="alert alert-danger"><?= $error; ?></div>
                    <?php endif; ?>

                    <?php if (!$success): ?>
                        <form method="POST">
                            <input type="hidden" name="username" value="<?= htmlspecialchars($username); ?>">

                            <div class="mb-3">
                                <label for="new_password" class="form-label">New Password</label>
                                <input type="password" name="new_password" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="confirm_password" class="form-label">Confirm Password</label>
                                <input type="password" name="confirm_password" class="form-control" required>
                            </div>

                            <button type="submit" class="btn btn-success">Reset Password</button>
                            <a href="student_login.php" class="btn btn-secondary float-end">Back to Login</a>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
