<?php
include '../config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['application_id'], $_POST['status'])) {
    $application_id = $_POST['application_id'];
    $status = $_POST['status'];

    $query = "UPDATE applications SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $status, $application_id);
    $stmt->execute();

    echo "<script>
            alert('The Application is $status.');
            window.location.href='new_applications.php';
          </script>";
    exit();
}
?>
