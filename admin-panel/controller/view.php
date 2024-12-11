<?php 

// Fetch user events
$events = [];
checkUserLoggedIn();
$userId = $_SESSION['user_id'];

$sql = "SELECT id, title, event_date, location, content FROM posts WHERE author_id = ?";
$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param("i", $userId);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $events[] = $row;
        }
    } else {
        $errorMessage = "Error fetching events: " . $stmt->error;
    }
    $stmt->close();
} else {
    $errorMessage = "Error: Unable to prepare the SQL statement.";
}

// Fetch user events
$allApprovedEvents = [];
checkUserLoggedIn();

$sql = "SELECT a.*, b.name AS userName FROM posts a LEFT JOIN users b ON a.author_id = b.id where a.approved = 1";
$stmt = $conn->prepare($sql);

if ($stmt) {

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $allApprovedEvents[] = $row;
        }
    } else {
        $errorMessage = "Error fetching events: " . $stmt->error;
    }
    $stmt->close();
} else {
    $errorMessage = "Error: Unable to prepare the SQL statement.";
}

// All events
$allEvents = [];
checkUserLoggedIn();

$sql = "SELECT a.*, b.name AS userName FROM posts a LEFT JOIN users b ON a.author_id = b.id";
$stmt = $conn->prepare($sql);

if ($stmt) {

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $allEvents[] = $row;
        }
    } else {
        $errorMessage = "Error fetching events: " . $stmt->error;
    }
    $stmt->close();
} else {
    $errorMessage = "Error: Unable to prepare the SQL statement.";
}

// Admin summary dashboard
// Total Users
$userSql = "SELECT COUNT(*) AS total_users FROM users";
$userResult = $conn->query($userSql);
$userData = $userResult->fetch_assoc();
$totalUsers = $userData['total_users'];

// Pending Events (approved = 0)
$pendingEventSql = "SELECT COUNT(*) AS pending_events FROM posts WHERE approved = 0";
$pendingEventResult = $conn->query($pendingEventSql);
$pendingEventData = $pendingEventResult->fetch_assoc();
$pendingEvents = $pendingEventData['pending_events'];

// Approved Events (approved = 1)
$approvedEventSql = "SELECT COUNT(*) AS approved_events FROM posts WHERE approved = 1";
$approvedEventResult = $conn->query($approvedEventSql);
$approvedEventData = $approvedEventResult->fetch_assoc();
$approvedEvents = $approvedEventData['approved_events'];

// Total Events
$totalEventSql = "SELECT COUNT(*) AS total_events FROM posts";
$totalEventResult = $conn->query($totalEventSql);
$totalEventData = $totalEventResult->fetch_assoc();
$totalEvents = $totalEventData['total_events'];

?>