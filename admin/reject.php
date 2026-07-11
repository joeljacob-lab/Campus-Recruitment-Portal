<?php
include("../config/db.php"); // Corrected file path

if(isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $conn->prepare("DELETE FROM recruiters WHERE id = ?");
    $stmt->bind_param("i", $id);

    if($stmt->execute()) {
        echo "<script>alert('Recruiter request rejected successfully.'); window.location.href='/campus-recruitment-portal/admin/recruiters_request.php';</script>";
    } else {
        echo "<script>alert('Error rejecting recruiter request.'); window.location.href='/campus-recruitment-portal/admin/recruiters_request.php';</script>";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "<script>alert('Invalid request.'); window.location.href='/campus-recruitment-portal/admin/recruiters_request.php';</script>";
}
?>
