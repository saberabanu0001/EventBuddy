<!-- <?php
// session_start();
// include 'C:/xampp/htdocs/EventBuddy/includes/db.php';

// $loginError = "";

// if ($_SERVER['REQUEST_METHOD'] == 'POST') {
//     $username = $_POST['username'];
//     $password = $_POST['password']; -->

    // Debugging
    // error_log("Username: $username");

    // Query to check if admin exists
    // $sql = "SELECT * FROM Users WHERE username = ? AND role = 'admin'";
    // $stmt = $conn->prepare($sql);
    // $stmt->bind_param("s", $username);
    // $stmt->execute();
    // $result = $stmt->get_result();

    // if ($result->num_rows === 1) {
    //     $user = $result->fetch_assoc();
    //     if (password_verify($password, $user['password_hash'])) {
    //         $_SESSION['user_id'] = $user['id'];
    //         $_SESSION['role'] = $user['role'];

            // Redirect to admin dashboard
//             header("Location: admin_dashboard.php");
//             exit();
//         } else {
//             $loginError = "Invalid password.";
//             error_log("Invalid password for user: $username");
//         }
//     } else {
//         $loginError = "Invalid username.";
//         error_log("Invalid username: $username");
//     }

//     $stmt->close();
//     $conn->close();
// }
// ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - EventBuddy</title>
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

    <!-- Navbar -->
    <!-- <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">EventBuddy</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="index.html">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="public/events.php">Events</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="register.php">Register</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="public/login.php">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="admin.php">Admin Login</a>
                    </li>
                </ul>
            </div> -->
            <!-- <div class="d-flex align-items-center">
                <a href="#" class="icon-link me-3" id="translateBtn"><i class="fas fa-globe"></i></a>
                <div id="google_translate_element" style="display:none;"></div>
                <form class="d-flex" id="searchForm">
                    <input class="form-control me-2" type="search" id="searchBox" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-success" type="submit">Search</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container" style="max-width: 500px; margin-top: 50px;" id="content">
        <h1>Admin Login</h1>

        (<?php if (!empty($loginError)): ?>
            <div class="alert alert-danger"><?php echo $loginError; ?></div>
       <?php endif; ?> )

        <form action="admin.php" method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">Username:</label>
                <input type="text" class="form-control" name="username" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password:</label>
                <input type="password" class="form-control" name="password" required>
            </div>

            <button type="submit" class="btn btn-primary">Login</button>
        </form>
    </div>

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
