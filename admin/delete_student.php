<?php
include '../config/db.php'; // Connect to the database

// Check if the student ID is provided in the URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Debugging: Print the ID to check if it's being received
    echo "Student ID to delete: " . $id . "<br>";

    // Prepare the DELETE query
    $query = "DELETE FROM students WHERE id = ?";
    $stmt = $conn->prepare($query);

    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }

    $stmt->bind_param("i", $id);

    // Execute the delete query
    if ($stmt->execute()) {
        echo "<script>alert('Student deleted successfully!'); window.location.href='manage_students.php';</script>";
    } else {
        die("Error executing delete: " . $stmt->error);
    }

    $stmt->close();
    $conn->close();
} else {
    die("Invalid request! No ID received.");
}
?>

