<?php
session_start();

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once '../../includes/db.php';

// Initialize messages
$successMessage = "";
$errorMessage = "";

// Function to sanitize input data
function sanitizeInput($data) {
    return htmlspecialchars(trim($data));
}

// Ensure the user is logged in
function checkUserLoggedIn() {
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['error' => "You must be logged in."]);
        exit;
    }
}

// Handle event creation
include_once '../controller/create.php';

// Handle Event view
include_once '../controller/view.php';

// Close database connection at the end
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="Event Management Web Portal" />
        <meta name="author" content="Sabera Banu" />
        <title>Student Dashboard</title>
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
        <link href="../css/styles.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    </head>
    <body class="sb-nav-fixed">
        <?php include('../layouts/header.php');?>
        
        <div id="layoutSidenav">
            <?php include('../layouts/sidebar.php');?>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">Student Dashboard</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active">Recent Activities/Updates</li>
                        </ol>
                        <div class="row">
                            <div class="col-xl-12">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <i class="fas fa-chart-area me-1"></i>
                                        List of all active events
                                    </div>
                                    <div class="card-body">
                                      <!-- Display Events -->
                                      <?php if (!empty($allApprovedEvents)): ?>
                                          <div class="table-responsive">
                                              <table class="table table-striped">
                                                  <thead>
                                                      <tr>
                                                          <th>Title</th>
                                                          <th>Date</th>
                                                          <th>Location</th>
                                                          <th>Details</th>
                                                          <th>Created by</th>
                                                          <th>Created date</th>
                                                      </tr>
                                                  </thead>
                                                  <tbody>
                                                      <?php foreach ($allApprovedEvents as $event): ?>
                                                          <tr>
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
                            <div class="col-xl-6">
                              <div class="card mb-4">
                                  <div class="card-header">
                                      <i class="fas fa-chart-bar me-1"></i>
                                      Create New Event
                                  </div>
                                  <div class="card-body">
                                      <div class="container" style=" margin-top: 10px;" id="content">
                                          <?php if (!empty($successMessage)): ?>
                                              <div class="alert alert-success"><?php echo $successMessage; ?></div>
                                          <?php endif; ?>

                                          <?php if (!empty($errorMessage)): ?>
                                              <div class="alert alert-danger"><?php echo $errorMessage; ?></div>
                                          <?php endif; ?>

                                          <form action="student-dashboard.php" method="POST">
                                              <?php include_once 'event-create-form.php'; ?>
                                          </form>

                                      </div>
                                  </div>
                              </div>
                          </div>

                            <div class="col-xl-6">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <i class="fas fa-chart-bar me-1"></i>
                                        My created event list
                                    </div>
                                    <div class="card-body">
                                      <?php if (isset($_GET['status']) && $_GET['status'] == 'success') : ?>
                                          <div class="alert alert-success mt-3">
                                              Event has been successfully deleted.
                                          </div>
                                      <?php endif; ?>

                                    <?php if (!empty($events)): ?>
                                        <div class="table-responsive">
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Title</th>
                                                        <th>Date</th>
                                                        <th>Location</th>
                                                        <th>Details</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($events as $index => $event): ?>
                                                        <tr>
                                                            <td><?php echo $index + 1; ?></td>
                                                            <td><?php echo htmlspecialchars($event['title']); ?></td>
                                                            <td><?php echo htmlspecialchars($event['event_date']); ?></td>
                                                            <td><?php echo htmlspecialchars($event['location']); ?></td>
                                                            <td><?php echo htmlspecialchars($event['content']); ?></td>
                                                            <td>
                                                                <a href="../controller/edit_event.php?id=<?php echo $event['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                                                                <a href="../controller/delete_event.php?id=<?php echo $event['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this event?');">Delete</a>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    <?php else: ?>
                                        <p>No events created yet.</p>
                                    <?php endif; ?>
                                </div>


                                </div>
                            </div>
                        </div>
                </main>
                <?php include('../layouts/footer.php');?>
                
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="assets/demo/chart-area-demo.js"></script>
        <script src="assets/demo/chart-bar-demo.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
        <script src="../js/datatables-simple-demo.js"></script>
    </body>
</html>
