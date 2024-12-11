<?php
// Handle event creation
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    checkUserLoggedIn();

    // Retrieve and sanitize input data
    $userId = $_SESSION['user_id'];
    $eventTitle = sanitizeInput($_POST['title']);  //to clean input data and avoid XSS attack
    $eventDate = sanitizeInput($_POST['event_date']);
    $eventLocation = sanitizeInput($_POST['location']);
    $eventDetails = sanitizeInput($_POST['content']);

    // Validate inputs
    if (empty($eventTitle) || empty($eventDate) || empty($eventLocation) || empty($eventDetails)) {
        $errorMessage = "All fields are required.";
    } else {
        // Insert event into the database
        $sql = "INSERT INTO posts (title, event_date, location, content, author_id) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("ssssi", $eventTitle, $eventDate, $eventLocation, $eventDetails, $userId);

            if ($stmt->execute()) {
                $successMessage = "Event created successfully!";
            } else {
                $errorMessage = "Error: Unable to create event. Please try again.";
            }
            $stmt->close();
        } else {
            $errorMessage = "Error: Unable to prepare the SQL statement.";
        }
    }
}

?>