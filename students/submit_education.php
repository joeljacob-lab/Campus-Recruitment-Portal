<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    echo "User not logged in.";
    exit;
}

$user_id = $_SESSION['user_id'];

$tenth_board = $_POST['tenth_board'];
$tenth_year = $_POST['tenth_year'];
$tenth_percentage = $_POST['tenth_percentage'];
$tenth_cgpa = $_POST['tenth_cgpa'];

$twelfth_board = $_POST['twelfth_board'];
$twelfth_year = $_POST['twelfth_year'];
$twelfth_percentage = $_POST['twelfth_percentage'];
$twelfth_cgpa = $_POST['twelfth_cgpa'];

$grad_board = $_POST['grad_board'];
$grad_year = $_POST['grad_year'];
$grad_percentage = $_POST['grad_percentage'];
$grad_cgpa = $_POST['grad_cgpa'];

$postgrad_board = $_POST['postgrad_board'] ?? NULL;
$postgrad_year = $_POST['postgrad_year'] ?? NULL;
$postgrad_percentage = $_POST['postgrad_percentage'] ?? NULL;
$postgrad_cgpa = $_POST['postgrad_cgpa'] ?? NULL;

$extra_curricular = $_POST['extra_curricular'] ?? NULL;
$other_achievement = $_POST['other_achievement'] ?? NULL;

$sql = "UPDATE students SET 
            tenth_board = ?, tenth_year = ?, tenth_percentage = ?, tenth_cgpa = ?,
            twelfth_board = ?, twelfth_year = ?, twelfth_percentage = ?, twelfth_cgpa = ?,
            grad_board = ?, grad_year = ?, grad_percentage = ?, grad_cgpa = ?,
            postgrad_board = ?, postgrad_year = ?, postgrad_percentage = ?, postgrad_cgpa = ?,
            extra_curricular = ?, other_achievement = ?
        WHERE id = ?";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo "Error preparing statement: " . $conn->error;
    exit;
}

$stmt->bind_param('sisdsisdsisdsisdssi', 
    $tenth_board, $tenth_year, $tenth_percentage, $tenth_cgpa,
    $twelfth_board, $twelfth_year, $twelfth_percentage, $twelfth_cgpa,
    $grad_board, $grad_year, $grad_percentage, $grad_cgpa,
    $postgrad_board, $postgrad_year, $postgrad_percentage, $postgrad_cgpa,
    $extra_curricular, $other_achievement, $user_id
);



if ($stmt->execute()) {
    header('Location: profile_update.php');
    exit;
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
