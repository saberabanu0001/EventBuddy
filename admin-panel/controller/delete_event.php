<?php
session_start();
include_once '../../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$eventId = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($eventId > 0) {
    $sql = "DELETE FROM posts WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("i", $eventId);

        if ($stmt->execute()) {
            // Redirect back to the dashboard with a success message
            header("Location: ../view/student-dashboard.php?status=success");
            exit;
        } else {
            echo "Error: Unable to delete the event.";
        }
        $stmt->close();
    } else {
        echo "Error: Unable to prepare the SQL statement.";
    }
} else {
    echo "Invalid event ID.";
}

$conn->close();
?>
