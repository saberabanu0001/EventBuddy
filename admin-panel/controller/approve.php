<?php
ob_start();  // Output buffering

include_once '../../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $eventId = intval($_POST['id']);
    $approvedStatus = intval($_POST['approved']);

    if ($eventId > 0) {
        $updateSql = "UPDATE posts SET approved = ? WHERE id = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param('ii', $approvedStatus, $eventId);

        if ($updateStmt->execute()) {
            header('Location: ../view/admin-dashboard.php?status=success&message=Event approved successfully');
            exit;
        } else {
            header('Location: ../view/admin-dashboard.php?status=error&message=Failed to approve the event');
            exit;
        }
        $updateStmt->close();
    }
}
ob_end_flush();  // Flush the output buffer
?>

