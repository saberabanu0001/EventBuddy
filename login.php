<?php
session_start(); // Ensure session is started
include 'includes/db.php';

$loginError = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM Users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];

            // print_r($user); die();

            // Redirection based on role
            if ($user['role'] === 'admin') {
                header("Location: admin-panel/view/admin-dashboard.php");
            } if ($user['role'] === 'student') {
                header("Location: admin-panel/view/student-dashboard.php");
            } else {
                // header("Location: public/events.php");
            }
            exit(); // Ensure exit after redirection
        } else {
            $loginError = "Invalid password.";
        }
    } else {
        $loginError = "Invalid username.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - EventBuddy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        .highlight {
            background-color: yellow;
        }
        #translateContainer {
            position: fixed;
            right: 20px;
            top: 70px;
            z-index: 1000;
            display: none;
            background-color: white;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">EventBuddy</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <?php include('includes/header.php'); ?>
            <div class="d-flex align-items-center">
                <a href="#" id="translateBtn" class="icon-link me-3"><i class="fas fa-globe"></i></a>
                <div id="google_translate_element" style="display:none;"></div>
                <form class="d-flex" id="searchForm">
                    <input class="form-control me-2" type="search" id="searchBox" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-success" type="submit">Search</button>
                </form>
            </div>
        </div>
    </div>
</nav>

<!-- Google Translate Element Container (Initially hidden) -->
<div id="translateContainer">
    <div id="google_translate_element"></div>
</div>

<div class="container" style="max-width: 500px; margin-top: 50px;" id="content">
    <h1>Login</h1>

    <?php if (!empty($loginError)): ?>
        <div class="alert alert-danger"><?php echo $loginError; ?></div>
    <?php endif; ?>

    <form action="login.php" method="POST">
        <div class="mb-3">
            <label for="username" class="form-label">Username:</label>
            <input type="text" class="form-control" name="username" required>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password:</label>
            <input type="password" class="form-control" name="password" required>
        </div>

        <button type="submit" class="btn btn-primary">Login</button>

        <!-- Link to Register Page -->
        <div class="mt-3">
            <p>Don't have an account? <a href="register.php">Click here to Register</a></p>
        </div>
    </form>
</div>


    <!-- Google Translate Script -->
    <script type="text/javascript">
        function googleTranslateElementInit() {
            new google.translate.TranslateElement({
                pageLanguage: 'en',
                layout: google.translate.TranslateElement.InlineLayout.SIMPLE,
                includedLanguages: 'en,bn,ko',
                autoDisplay: false
            }, 'google_translate_element');
        }

        document.getElementById('translateBtn').addEventListener('click', function() {
            var translateContainer = document.getElementById('translateContainer');
            translateContainer.style.display = (translateContainer.style.display === 'none' || translateContainer.style.display === '') ? 'block' : 'none';
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
