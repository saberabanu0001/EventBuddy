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

// Approve event
include_once '../controller/approve.php';

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
        <title>Admin Dashboard</title>
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
        <link href="../css/styles.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
        <style>
          .btn-circle {
              width: 40px;
              height: 40px;
              padding: 0;
              text-align: center;
              border-radius: 50%;
              display: inline-flex;
              align-items: center;
              justify-content: center;
              font-weight: bold;
          }
          .btn-circle i {
              font-size: 18px;
          }
      </style>
    </head>
    <body class="sb-nav-fixed">
        <?php include('../layouts/header.php');?>
        
        <div id="layoutSidenav">
            <?php include('../layouts/sidebar.php');?>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">Admin Dashboard</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>
                        <!-- Dashboard Cards -->
                        <div class="row">
                            <!-- Total Users -->
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-primary text-white mb-4">
                                    <div class="card-body">Total Users: <?php echo $totalUsers; ?></div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <a class="small text-white stretched-link" href="#">View Details</a>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Pending Events -->
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-warning text-white mb-4">
                                    <div class="card-body">Pending Events: <?php echo $pendingEvents; ?></div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <a class="small text-white stretched-link" href="#">View Details</a>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Approved Events -->
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-success text-white mb-4">
                                    <div class="card-body">Approved Events: <?php echo $approvedEvents; ?></div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <a class="small text-white stretched-link" href="#">View Details</a>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Total Events -->
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-danger text-white mb-4">
                                    <div class="card-body">Total Events: <?php echo $totalEvents; ?></div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <a class="small text-white stretched-link" href="#">View Details</a>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>

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
                                      <?php if (!empty($allEvents)): ?>
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
                                                          <th>Created date</th>
                                                          <th>Status</th>

                                                          <th>Approve/Disapprove</th>
                                                      </tr>
                                                  </thead>
                                                  <tbody>
                                                      <?php $i=1; foreach ($allEvents as $event): ?>
                                                          <tr>
                                                              <td><?php echo $i++;  ?></td>
                                                              <td><?php echo htmlspecialchars($event['title']); ?></td>
                                                              <td><?php echo htmlspecialchars($event['event_date']); ?></td>
                                                              <td><?php echo htmlspecialchars($event['location']); ?></td>
                                                              <td><?php echo htmlspecialchars($event['content']); ?></td>
                                                              <td><?php echo htmlspecialchars($event['userName']); ?></td>
                                                              <td><?php echo htmlspecialchars($event['created_at']); ?></td>
                                                              <td>
                                                                  <?php if ($event['approved'] == 1): ?>
                                                                      <button class="btn btn-success btn-sm" disabled>Approved</button>
                                                                  <?php elseif ($event['approved'] == 0): ?>
                                                                      <button class="btn btn-warning btn-sm" disabled>Pending</button>
                                                                  <?php else: ?>
                                                                      <button class="btn btn-danger btn-sm" disabled>Rejected</button>
                                                                  <?php endif; ?>
                                                              </td>
                                                              
                                                              <td>
                                                              <?php if ($event['approved'] == 0): ?>
                                                                  <form action="../controller/approve.php" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure you want to approve this event?');">
                                                                      <input type="hidden" name="id" value="<?php echo $event['id']; ?>">
                                                                      <input type="hidden" name="approved" value="1">
                                                                      <button type="submit" class="btn btn-success btn-circle btn-sm">
                                                                          <i class="fas fa-check"></i>
                                                                      </button>
                                                                  </form>

                                                              <?php else: ?>
                                                                  <form action="../controller/approve.php" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure you want to approve this event?');">
                                                                      <input type="hidden" name="id" value="<?php echo $event['id']; ?>">
                                                                      <input type="hidden" name="approved" value="0">
                                                                      <button type="submit" class="btn btn-danger btn-circle btn-sm">
                                                                          <i class="fas fa-check"></i>
                                                                      </button>
                                                                  </form>
                                                              <?php endif; ?>    
                                                              </td>
                                                              
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
                        <div class="row">
                          <div class="col-xl-12">
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

                                          <form action="admin-dashboard.php" method="POST">
                                              <?php include_once 'event-create-form.php'; ?>
                                          </form>

                                      </div>
                                  </div>
                              </div>
                          </div>
                        </div>
                </main>
                <?php include('../layouts/footer.php');?>
                
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="../js/scripts.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="../assets/demo/chart-area-demo.js"></script>
        <script src="../assets/demo/chart-bar-demo.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
        <script src="../js/datatables-simple-demo.js"></script>
    </body>
</html>
