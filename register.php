<?php
include 'includes/db.php';

$successMessage = "";
$errorMessage = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $student_id = $_POST['student_id'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    if (!in_array($role, ['student', 'admin'])) {
        $errorMessage = "Invalid role selected.";
    } else {
        $sql_check_student_id = "SELECT * FROM Users WHERE student_id = ?";
        $stmt_check_student_id = $conn->prepare($sql_check_student_id);
        $stmt_check_student_id->bind_param("s", $student_id);
        $stmt_check_student_id->execute();
        $result_check_student_id = $stmt_check_student_id->get_result();

        $sql_check_username = "SELECT * FROM Users WHERE username = ?";
        $stmt_check_username = $conn->prepare($sql_check_username);
        $stmt_check_username->bind_param("s", $username);
        $stmt_check_username->execute();
        $result_check_username = $stmt_check_username->get_result();

        if ($result_check_student_id->num_rows > 0) {
            $errorMessage = "The student ID already exists. Please choose a different one.";
        } elseif ($result_check_username->num_rows > 0) {
            $errorMessage = "The username already exists. Please choose a different one.";
        } else {
            $password_hash = password_hash($password, PASSWORD_BCRYPT);
            $sql = "INSERT INTO Users (name, student_id, username, password_hash, role) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssss", $name, $student_id, $username, $password_hash, $role);

            if ($stmt->execute()) {
                if ($role === 'admin') {
                    header("Location: login.php");
                    exit();
                } else {
                    header("Location: login.php");
                    exit();
                }
            } else {
                $errorMessage = "Error: " . $stmt->error;
            }

            $stmt->close();
        }

        $stmt_check_student_id->close();
        $stmt_check_username->close();
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - EventBuddy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        .highlight {
            background-color: yellow;
        }
    </style>
</head>
<body>

    <!-- Navbar for both register.php and login.php -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">EventBuddy</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <?php include('includes/header.php'); ?>
        </div>

        <!-- Right-aligned Icons -->
        <div class="d-flex align-items-center">
            <a href="#" id="translateBtn" class="icon-link me-3"><i class="fas fa-globe"></i></a>
            <form class="d-flex" id="searchForm">
                <input class="form-control me-2" type="search" id="searchBox" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-success" type="submit">Search</button>
            </form>
        </div>
    </div>
</nav>


    <div class="container" style="max-width: 500px; margin-top: 50px;" id="content">
        <h1>Register</h1>

        <?php if (!empty($successMessage)): ?>
            <div class="alert alert-success"><?php echo $successMessage; ?></div>
        <?php endif; ?>

        <?php if (!empty($errorMessage)): ?>
            <div class="alert alert-danger"><?php echo $errorMessage; ?></div>
        <?php endif; ?>

        <form action="register.php" method="POST">
            <div class="mb-3">
                <label for="name" class="form-label">Name:</label>
                <input type="text" class="form-control" name="name" required>
            </div>

            <div class="mb-3">
                <label for="student_id" class="form-label">Student ID:</label>
                <input type="text" class="form-control" name="student_id" required>
            </div>

            <div class="mb-3">
                <label for="username" class="form-label">Username:</label>
                <input type="text" class="form-control" name="username" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password:</label>
                <input type="password" class="form-control" name="password" required>
            </div>

            <div class="mb-3">
                <label for="role" class="form-label">Role:</label>
                <select class="form-control" name="role">
                    <option value="student">Student</option>
                    <option value="admin">Admin</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Register</button>
            <!-- Link to login Page -->
            <div class="mt-3">
                <p>Do you have an account? <a href="EventBuddy/login.php">Click here to login</a></p>
            </div>
        </form>
    </div>

    <!-- Google Translate Script -->
    <div id="google_translate_element" class="google-translate-container" style="display: none;"></div>

    <script type="text/javascript">
        function googleTranslateElementInit() {
            new google.translate.TranslateElement({
                pageLanguage: 'en',  // Set default language to English
                layout: google.translate.TranslateElement.InlineLayout.SIMPLE,  // Simple inline layout
                includedLanguages: 'en,bn,ko',  // Add more languages if needed
                autoDisplay: false  // Disable auto display
            }, 'google_translate_element');
        }

        // Toggle visibility of Google Translate when the globe icon is clicked
        document.addEventListener('DOMContentLoaded', function() {
            var translateBtn = document.getElementById('translateBtn');
            var translateElement = document.getElementById('google_translate_element');
            
            if (translateBtn) {
                translateBtn.addEventListener('click', function() {
                    // Toggle the visibility of the dropdown
                    translateElement.style.display = (translateElement.style.display === 'none' || translateElement.style.display === '') ? 'block' : 'none';
                    
                    // Position the dropdown near the button
                    var rect = translateBtn.getBoundingClientRect();
                    translateElement.style.position = 'absolute';
                    translateElement.style.top = rect.bottom + window.scrollY + 'px'; // Place below the button
                    translateElement.style.left = rect.left + 'px'; // Align with the button horizontally
                });
            }
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
