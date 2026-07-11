<?php
include '../config/db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "DELETE FROM recruiters WHERE id=$id";
    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Recruiter Rejected!'); window.location='recruiters_request.php';</script>";
    } else {
        echo "Error rejecting recruiter: " . mysqli_error($conn);
    }
}
?>

