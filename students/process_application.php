<?php
session_start();
include '../config/db.php'; // Database connection

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    die("❌ Error: You must be logged in to apply for a job.");
}

$user_id = $_SESSION['user_id']; // Student ID
$job_id = $_POST['job_id'];
$recruiter_id = $_POST['recruiter_id'];
$apply_date = date('Y-m-d');

// Fetch job details
$query = "SELECT last_date FROM jobs WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $job_id);
$stmt->execute();
$result = $stmt->get_result();
$job = $result->fetch_assoc();

if (!$job) {
    die("❌ Error: Job not found.");
}

// Check if application date is within valid range
if ($apply_date > $job['last_date']) {
    die("❌ Error: Job application deadline has passed.");
}

// Prevent duplicate applications
$check_query = "SELECT id FROM applications WHERE user_id = ? AND job_id = ?";
$check_stmt = $conn->prepare($check_query);
$check_stmt->bind_param("ii", $user_id, $job_id);
$check_stmt->execute();
$check_result = $check_stmt->get_result();
if ($check_result->num_rows > 0) {
    die("❌ Error: You have already applied for this job.");
}

// Allowed file types
$allowed_resume_types = ['application/pdf'];
$allowed_image_types = ['image/jpeg', 'image/png'];

// File Upload Handling
$resume_dir = "../uploads/resumes/";
$image_dir = "../uploads/photos/";

if (!file_exists($resume_dir)) mkdir($resume_dir, 0777, true);
if (!file_exists($image_dir)) mkdir($image_dir, 0777, true);

// Validate file uploads
if (!isset($_FILES['resume']) || !isset($_FILES['photo'])) {
    die("❌ Error: Resume and photo are required.");
}

$resume_type = $_FILES["resume"]["type"];
$image_type = $_FILES["photo"]["type"];

if (!in_array($resume_type, $allowed_resume_types)) {
    die("❌ Error: Only PDF files are allowed for resumes.");
}
if (!in_array($image_type, $allowed_image_types)) {
    die("❌ Error: Only JPEG and PNG files are allowed for photos.");
}

// Generate unique filenames to prevent overwriting
$resume_filename = time() . "_" . basename($_FILES["resume"]["name"]);
$image_filename = time() . "_" . basename($_FILES["photo"]["name"]);

$resume_path = $resume_dir . $resume_filename;
$image_path = $image_dir . $image_filename;

// Move uploaded files
if (!move_uploaded_file($_FILES["resume"]["tmp_name"], $resume_path) ||
    !move_uploaded_file($_FILES["photo"]["tmp_name"], $image_path)) {
    die("❌ Error: Failed to upload files.");
}

// Insert application into database
$insert_query = "INSERT INTO applications (user_id, job_id, recruiter_id, apply_date, resume_path, image_path) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($insert_query);
$stmt->bind_param("iiisss", $user_id, $job_id, $recruiter_id, $apply_date, $resume_path, $image_path);

if ($stmt->execute()) {
    echo "✅ You have successfully applied for this job.";
    echo "<script>setTimeout(() => window.location.href = 'dashboard.php', 3000);</script>";
} else {
    die("❌ Error: Failed to submit application.");
}
?>
