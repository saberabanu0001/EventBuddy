<?php
include_once '../db.php'; // Include the connection file

session_start();

// Check if the user is logged in, if not, redirect to login page
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

// Fetch events from the database
$query = "SELECT * FROM events ORDER BY created_at DESC";
$result = $conn->query($query);

if (!$result) {
    die("Query failed: " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> <!-- Use CDN for Bootstrap -->
</head>
<body>
    <div class="container">
        <h1 class="my-4">Upcoming Events</h1>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="card mb-3">
                    <div class="card-body">
                        <h4 class="card-title"><?php echo htmlspecialchars($row['title']); ?></h4>
                        <p class="card-text"><?php echo htmlspecialchars($row['description']); ?></p>
                        <small class="text-muted">Posted on <?php echo $row['created_at']; ?> by User ID <?php echo $row['created_by']; ?></small>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No events available at the moment.</p>
        <?php endif; ?>
    </div>
</body>
</html>
