<?php
session_start();
include_once '../../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get the event ID from URL
$eventId = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input data
    $eventTitle = htmlspecialchars(trim($_POST['title']));
    $eventDate = htmlspecialchars(trim($_POST['event_date']));
    $eventLocation = htmlspecialchars(trim($_POST['location']));
    $eventDetails = htmlspecialchars(trim($_POST['content']));

    if (empty($eventTitle) || empty($eventDate) || empty($eventLocation) || empty($eventDetails)) {
        $errorMessage = "All fields are required.";
    } else {
        // Update event query
        $sql = "UPDATE posts SET title = ?, event_date = ?, location = ?, content = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("ssssi", $eventTitle, $eventDate, $eventLocation, $eventDetails, $eventId);

            if ($stmt->execute()) {
                $successMessage = "Event updated successfully.";
            } else {
                $errorMessage = "Error: Unable to update the event.";
            }
            $stmt->close();
        } else {
            $errorMessage = "Error: Unable to prepare the SQL statement.";
        }
    }
}

# Fetch event data to populate the form
$sql = "SELECT * FROM posts WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $eventId);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $event = $row;
} else {
    echo "Event not found.";
    exit;
}
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Event</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <h2>Edit Event</h2>

    <?php if (isset($successMessage)) : ?>
        <div class="alert alert-success"><?php echo $successMessage; ?></div>
    <?php endif; ?>

    <?php if (isset($errorMessage)) : ?>
        <div class="alert alert-danger"><?php echo $errorMessage; ?></div>
    <?php endif; ?>

    <form action="edit_event.php?id=<?php echo $eventId; ?>" method="POST">
        <div class="form-group">
            <label for="title">Event Title</label>
            <input type="text" id="title" name="title" class="form-control" value="<?php echo htmlspecialchars($event['title']); ?>" required>
        </div>

        <div class="form-group">
            <label for="event_date">Event Date</label>
            <input type="date" id="event_date" name="event_date" class="form-control" value="<?php echo htmlspecialchars($event['event_date']); ?>" required>
        </div>

        <div class="form-group">
            <label for="location">Location</label>
            <input type="text" id="location" name="location" class="form-control" value="<?php echo htmlspecialchars($event['location']); ?>" required>
        </div>

        <div class="form-group">
            <label for="content">Details</label>
            <textarea id="content" name="content" class="form-control" rows="4" required><?php echo htmlspecialchars($event['content']); ?></textarea>
        </div>

        <button type="submit" class="btn btn-primary mt-2">Update Event</button>
    </form>
    <a href="../view/student-dashboard.php">
         <button type="button" class="btn btn-secondary mt-2">Back to my Dashboard</button>
    </a>
</div>

</body>
</html>
