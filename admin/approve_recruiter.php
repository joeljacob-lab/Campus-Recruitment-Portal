<?php
include("../config/db.php"); // Ensure correct file path

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $query = "UPDATE recruiters SET status='approved' WHERE id=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "<script>alert('Recruiter Approved!'); window.location.href='/campus-recruitment-portal/admin/recruiters_request.php';</script>";
    } else {
        echo "<script>alert('Error approving recruiter!'); window.location.href='/campus-recruitment-portal/admin/recruiters_request.php';</script>";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "<script>alert('Invalid request!'); window.location.href='/campus-recruitment-portal/admin/recruiters_request.php';</script>";
}
?>


