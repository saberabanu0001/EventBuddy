<?php
include_once '../db.php';
session_start();

// Check if user is admin or faculty
if ($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'faculty') {
    header("Location: events.php");
    exit;
}

if (isset($_POST['submit'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $created_by = $_SESSION['user_id'];

    $query = "INSERT INTO events (title, description, created_by, created_at) VALUES (?, ?, ?, NOW())";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ssi', $title, $description, $created_by);

    if ($stmt->execute()) {
        echo "Event created successfully!";
        header("Location: events.php");
    } else {
        echo "Error creating event.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Event</title>
    <link rel="stylesheet" href="../path/to/bootstrap.css">
</head>
<body>
    <div class="container">
        <h1>Create a New Event</h1>
        <form action="create_event.php" method="POST">
            <div class="form-group">
                <label for="title">Event Title</label>
                <input type="text" class="form-control" name="title" required>
            </div>
            <div class="form-group">
                <label for="description">Event Description</label>
                <textarea class="form-control" name="description" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary" name="submit">Create Event</button>
        </form>
    </div>
</body>
</html>
