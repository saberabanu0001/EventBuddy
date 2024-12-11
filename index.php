<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once 'includes/db.php';

// Handle Event view
// All events public
$allEventsPublic = [];

$sql = "SELECT a.*, b.name AS userName FROM posts a LEFT JOIN users b ON a.author_id = b.id where approved = 1";
$stmt = $conn->prepare($sql);

if ($stmt) {

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $allEventsPublic[] = $row;
        }
    } else {
        $errorMessage = "Error fetching events: " . $stmt->error;
    }
    $stmt->close();
} else {
    $errorMessage = "Error: Unable to prepare the SQL statement.";
}
// Close database connection at the end
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EventBuddy</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <!-- Font Awesome CDN for Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        .highlight {
            background-color: yellow;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">EventBuddy</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <?php include('includes/header.php'); ?>
                <div class="d-flex align-items-center">
                    <a href="#" class="icon-link me-3" id="translateBtn"><i class="fas fa-globe"></i></a>
                    <div id="google_translate_element" style="display:none;"></div>
                    <form class="d-flex" id="searchForm">
                        <input class="form-control me-2" type="search" id="searchBox" placeholder="Search" aria-label="Search">
                        <button class="btn btn-outline-success" type="submit">Search</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div style="margin-left: 50px; margin-right: 50px;" id="content">
        <h1>Welcome to EventBuddy</h1>
        <p>Your one-stop solution for managing and participating in events at Sejong University.</p>
        
        <div class="row">
            <div class="col-xl-12">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-chart-area me-1"></i>
                        List of all events
                    </div>
                    <div class="card-body">
                        <!-- Check if there's a message passed via the URL -->
                        <?php if (isset($_GET['status']) && isset($_GET['message'])): ?>
                            <div class="alert alert-<?php echo ($_GET['status'] === 'success') ? 'success' : 'danger'; ?> alert-dismissible fade show" role="alert">
                                <?php echo htmlspecialchars($_GET['message']); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>
                        <!-- Display Events -->
                        <?php if (!empty($allEventsPublic)): ?>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>S/N</th>
                                            <th>Title</th>
                                            <th>Date</th>
                                            <th>Location</th>
                                            <th>Details</th>
                                            <th>Created by</th>
                                            <th>Published date</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i=1; foreach ($allEventsPublic as $event): ?>
                                            <tr>
                                                <td><?php echo $i++;  ?></td>
                                                <td><?php echo htmlspecialchars($event['title']); ?></td>
                                                <td><?php echo htmlspecialchars($event['event_date']); ?></td>
                                                <td><?php echo htmlspecialchars($event['location']); ?></td>
                                                <td><?php echo htmlspecialchars($event['content']); ?></td>
                                                <td><?php echo htmlspecialchars($event['userName']); ?></td>
                                                <td><?php echo htmlspecialchars($event['created_at']); ?></td>
                                                
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <p>No events are currently active.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--Code for the translation-->
    <script type="text/javascript">
        function googleTranslateElementInit() {
            new google.translate.TranslateElement(
                {
                    pageLanguage: 'en',
                    layout: google.translate.TranslateElement.InlineLayout.SIMPLE,
                    includedLanguages: 'en,bn,ko',
                    autoDisplay: false 
                },
                'google_translate_element'
            );
        }
        document.getElementById('translateBtn').addEventListener('click', function() {
            var translateElement = document.getElementById('google_translate_element');
            translateElement.style.display = (translateElement.style.display === 'none' || translateElement.style.display === '') ? 'block' : 'none';
        });


        // Function to highlight text
        function highlightText(node, query) {
            var regex = new RegExp('(' + query + ')', 'gi');
            if (node.nodeType === 3) { // Text node
                var span = document.createElement('span');
                span.innerHTML = node.textContent.replace(regex, '<span class="highlight">$1</span>');
                node.parentNode.replaceChild(span, node);
            } else if (node.nodeType === 1 && node.childNodes) { // Element node
                node.childNodes.forEach(child => highlightText(child, query));
            }
        }

        // Search functionality with highlight


                // Search functionality with highlight
                document.getElementById('searchForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent form from submitting normally
            var query = document.getElementById('searchBox').value.toLowerCase();
            var content = document.getElementById('content');

            // Remove previous highlights
            document.querySelectorAll('.highlight').forEach(function(highlight) {
                var parent = highlight.parentNode;
                parent.replaceChild(document.createTextNode(highlight.textContent), highlight);
                parent.normalize();
            });

            // Highlight new search terms
            highlightText(content, query);
        });
    </script>

    <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
