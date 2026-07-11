<?php
session_start();
include '../config/db.php';

// Ensure only students can access this page
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student') {
    header('Location: student_login.php');
    exit();
}

$student_id = $_SESSION['user_id'];

// Query to count total applications made by the student
$query = "SELECT COUNT(*) AS total_applications FROM applications WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$total_applications = $row['total_applications'];

// Shortlisted Applications
$shortlisted_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM applications WHERE user_id='$student_id' AND status='shortlisted'");
$shortlisted_row = mysqli_fetch_assoc($shortlisted_query);
$shortlisted_count = $shortlisted_row['total'];

// Rejected Applications
$rejected_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM applications WHERE user_id='$student_id' AND status='rejected'");
$rejected_row = mysqli_fetch_assoc($rejected_query);
$rejected_count = $rejected_row['total'];

//Today's Applied Jobs
$today_query = mysqli_query($conn, "
    SELECT COUNT(*) AS total 
    FROM applications 
    WHERE user_id = '$student_id' 
      AND DATE(apply_date) = CURDATE()
");
$today_row = mysqli_fetch_assoc($today_query);
$todays_applied_jobs= $today_row['total'];

// Yesterday's Applied Jobs
$yesterday_query = mysqli_query($conn, "
    SELECT COUNT(*) AS total 
    FROM applications 
    WHERE user_id = '$student_id' 
      AND DATE(apply_date) = CURDATE() - INTERVAL 1 DAY
");
$yesterday_row = mysqli_fetch_assoc($yesterday_query);
$yesterdays_applied_jobs = $yesterday_row['total'];

// Last 7 Days' Applied Jobs (including today)
$last7days_query = mysqli_query($conn, "
    SELECT COUNT(*) AS total 
    FROM applications 
    WHERE user_id = '$student_id' 
      AND DATE(apply_date) >= CURDATE() - INTERVAL 6 DAY
");
$last7days_row = mysqli_fetch_assoc($last7days_query);
$last7_applied_jobs = $last7days_row['total'];

//Pending Applications
$pending_query = mysqli_query($conn, "
    SELECT COUNT(*) AS total 
    FROM applications 
    WHERE user_id = '$student_id' 
      AND status = 'pending'
");
$pending_row = mysqli_fetch_assoc($pending_query);
$pending_count = $pending_row['total'];

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
      /* Common Styles */
body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            min-height: 100vh;
            overflow-x: hidden;
        }
      /* Consistent Sidebar Styling (paste this in all pages) */
/* Sidebar Specific Styles (scoped with sidebar- prefix) */
:root {
            --sidebar-bg: #1a1a2e;
            --sidebar-hover: #16213e;
            --sidebar-active: #0f3460;
            --sidebar-accent: #e94560;
            --sidebar-text-primary: #f1f1f1;
            --sidebar-text-secondary: #b8b8b8;
            --sidebar-online: #4ade80;
            --sidebar-transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            --sidebar-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        }

        .sidebar-container {
            background: var(--sidebar-bg);
            width: 280px;
            min-height: 100vh;
            position: fixed;
            transition: var(--sidebar-transition);
            z-index: 1000;
            box-shadow: var(--sidebar-shadow);
            overflow-y: auto;
        }

        .sidebar-header {
            padding: 30px 20px 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid var(--sidebar-accent);
            padding: 3px;
            transition: var(--sidebar-transition);
            margin-bottom: 15px;
        }

        .sidebar-avatar:hover {
            transform: scale(1.05);
            box-shadow: 0 0 20px rgba(233, 69, 96, 0.5);
        }

        .sidebar-username {
            color: var(--sidebar-text-primary);
            font-weight: 600;
            font-size: 1.2rem;
            margin-bottom: 5px;
        }

        .sidebar-status {
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--sidebar-online);
            font-size: 0.85rem;
            margin-bottom: 0;
        }

        .sidebar-status::before {
            content: '';
            display: inline-block;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: var(--sidebar-online);
            margin-right: 8px;
            animation: sidebar-pulse 2s infinite;
        }

        @keyframes sidebar-pulse {
            0% { transform: scale(0.95); opacity: 1; }
            50% { transform: scale(1.1); opacity: 0.7; }
            100% { transform: scale(0.95); opacity: 1; }
        }

        .sidebar-nav {
            padding: 20px 0;
        }

        .sidebar-item {
            margin: 5px 15px;
            position: relative;
            overflow: hidden;
            border-radius: 6px;
        }

        .sidebar-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(233, 69, 96, 0.2), transparent);
            transition: 0.5s;
        }

        .sidebar-item:hover::before {
            left: 100%;
        }

        .sidebar-link {
            color: var(--sidebar-text-secondary);
            padding: 12px 20px;
            display: flex;
            align-items: center;
            border-radius: 6px;
            transition: var(--sidebar-transition);
            font-weight: 500;
            text-decoration: none;
            position: relative;
            z-index: 1;
        }

        .sidebar-link:hover {
            color: var(--sidebar-text-primary);
            background-color: var(--sidebar-hover);
            transform: translateX(5px);
        }

        .sidebar-link.active {
            color: var(--sidebar-text-primary);
            background-color: var(--sidebar-active);
            box-shadow: 0 4px 15px rgba(15, 52, 96, 0.4);
        }

        .sidebar-link i {
            margin-right: 12px;
            font-size: 1.1rem;
            width: 20px;
            text-align: center;
        }

        .sidebar-link.active::after {
            content: '';
            position: absolute;
            right: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 60%;
            background-color: var(--sidebar-accent);
            border-radius: 4px 0 0 4px;
        }
/* Main content margin to prevent sidebar overlap */
main {
  margin-left: 250px;
  padding: 1.5rem;
}

@media (max-width: 768px) {
  .sidebar {
    width: 80px;
    overflow: hidden;
  }
  .sidebar .nav-link span {
    display: none;
  }
  .sidebar .nav-link {
    text-align: center;
    padding: 0.75rem 0.5rem;
  }
  .sidebar .nav-link i {
    margin-right: 0;
    font-size: 1.2rem;
  }
  main {
    margin-left: 80px;
  }
}
      /* Student Dashboard Card Styles */
.card-student {
  border-radius: 10px;
  border: none;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
  transition: all 0.3s ease;
  overflow: hidden;
}

.card-student:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
}

.icon-circle {
  width: 44px;
  height: 44px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
}

.bg-primary-light { background-color: rgba(13, 110, 253, 0.1) }
.bg-success-light { background-color: rgba(25, 135, 84, 0.1) }
.bg-danger-light { background-color: rgba(220, 53, 69, 0.1) }
.bg-warning-light { background-color: rgba(255, 193, 7, 0.1) }
.bg-info-light { background-color: rgba(13, 202, 240, 0.1) }
.bg-purple-light { background-color: rgba(111, 66, 193, 0.1) }

.text-purple { color: #6f42c1 }
.border-purple { border-color: #6f42c1 !important }

.progress-thin {
  height: 4px;
  background-color: #f0f2f5;
  border-radius: 2px;
  overflow: hidden;
}

.progress-thin .progress-bar {
  height: 100%;
  border-radius: 2px;
}

.border-start {
  border-left-width: 4px !important;
}
</style>
</head>
<body>
  <div class="container-fluid">
    <div class="row">
      <!-- Sidebar -->
      <nav class="col-md-3 col-lg-2 d-md-block bg-dark sidebar" style="height: 100vh; position: fixed; width: 225px; overflow-y: auto;">
      <div class="position-sticky pt-3">
        <div class="text-center my-4">
        <img src="../assets/images/user-icon2.png" alt="Student" class="rounded-circle" width="80" height="80" />
          <h5 class="mt-2 text-white">Student</h5>
          <p class="text-success">● Online</p>
            </div>
            <ul class="nav flex-column">
                <li class="sidebar-item">
                    <a class="sidebar-link" href="student_profile.php">
                        <i class="fas fa-user"></i>
                        <span>Profile</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="student_settings.php">
                        <i class="fas fa-cog"></i>
                        <span>Settings</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="../logout.php">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link active" href="dashboard.php">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Home</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="view_vacancy.php">
                        <i class="fas fa-briefcase"></i>
                        <span>Apply Vacancies</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="history_appliedjobs.php">
                        <i class="fas fa-history"></i>
                        <span>Applied Jobs</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="education_form.php">
                        <i class="fas fa-graduation-cap"></i>
                        <span>Fill Education Form</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="manage_education_form.php">
                        <i class="fas fa-tasks"></i>
                        <span>Manage Education Form</span>
                    </a>
                </li>
            </ul>
        </div>
      </nav>
    </div>  

       <!-- Main Content -->
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4" style="margin-left: 18rem; margin-top: 2rem;">
      <h2>Student Dashboard</h2>
      <div class="row mt-4">
     <!-- Total Applied Jobs -->
<div class="col-md-4 mb-4">
  <div class="card card-student h-100 border-start border-primary border-4">
    <div class="card-body">
      <div class="d-flex justify-content-between align-items-start">
        <div>
          <h6 class="text-uppercase text-muted mb-2">Total Applied Jobs</h6>
          <h2 class="mb-0 text-primary"><?= $total_applications; ?></h2>
        </div>
        <div class="icon-circle bg-primary-light">
          <i class="fas fa-file-alt text-primary"></i>
        </div>
      </div>
      <div class="mt-3">
        <div class="progress-thin">
          <div class="progress-bar bg-primary" style="width: 100%"></div>
        </div>
        <small class="text-muted">All your applications</small>
      </div>
    </div>
  </div>
</div>

<!-- Shortlisted Applications -->
<div class="col-md-4 mb-4">
  <div class="card card-student h-100 border-start border-success border-4">
    <div class="card-body">
      <div class="d-flex justify-content-between align-items-start">
        <div>
          <h6 class="text-uppercase text-muted mb-2">Shortlisted</h6>
          <h2 class="mb-0 text-success"><?= $shortlisted_count; ?></h2>
        </div>
        <div class="icon-circle bg-success-light">
          <i class="fas fa-check-circle text-success"></i>
        </div>
      </div>
      <div class="mt-3">
        <div class="progress-thin">
          <div class="progress-bar bg-success" style="width: <?= $total_applications > 0 ? ($shortlisted_count/$total_applications)*100 : 0 ?>%"></div>
        </div>
        <small class="text-muted"><?= $total_applications > 0 ? round(($shortlisted_count/$total_applications)*100, 1) : 0 ?>% success rate</small>
      </div>
    </div>
  </div>
</div>

<!-- Rejected Applications -->
<div class="col-md-4 mb-4">
  <div class="card card-student h-100 border-start border-danger border-4">
    <div class="card-body">
      <div class="d-flex justify-content-between align-items-start">
        <div>
          <h6 class="text-uppercase text-muted mb-2">Rejected</h6>
          <h2 class="mb-0 text-danger"><?= $rejected_count; ?></h2>
        </div>
        <div class="icon-circle bg-danger-light">
          <i class="fas fa-times-circle text-danger"></i>
        </div>
      </div>
      <div class="mt-3">
        <div class="progress-thin">
          <div class="progress-bar bg-danger" style="width: <?= $total_applications > 0 ? ($rejected_count/$total_applications)*100 : 0 ?>%"></div>
        </div>
        <small class="text-muted"><?= $total_applications > 0 ? round(($rejected_count/$total_applications)*100, 1) : 0 ?>% of applications</small>
      </div>
    </div>
  </div>
</div>

<!-- Pending Applications -->
<div class="col-md-4 mb-4">
  <div class="card card-student h-100 border-start border-warning border-4">
    <div class="card-body">
      <div class="d-flex justify-content-between align-items-start">
        <div>
          <h6 class="text-uppercase text-muted mb-2">Pending</h6>
          <h2 class="mb-0 text-warning"><?= $pending_count; ?></h2>
        </div>
        <div class="icon-circle bg-warning-light">
          <i class="fas fa-hourglass-half text-warning"></i>
        </div>
      </div>
      <div class="mt-3">
        <div class="progress-thin">
          <div class="progress-bar bg-warning" style="width: <?= $total_applications > 0 ? ($pending_count/$total_applications)*100 : 0 ?>%"></div>
        </div>
        <small class="text-muted">Awaiting response</small>
      </div>
    </div>
  </div>
</div>

<!-- Today's Applied Jobs -->
<div class="col-md-4 mb-4">
  <div class="card card-student h-100 border-start border-info border-4">
    <div class="card-body">
      <div class="d-flex justify-content-between align-items-start">
        <div>
          <h6 class="text-uppercase text-muted mb-2">Today's Applications</h6>
          <h2 class="mb-0 text-info"><?= $todays_applied_jobs; ?></h2>
        </div>
        <div class="icon-circle bg-info-light">
          <i class="fas fa-calendar-day text-info"></i>
        </div>
      </div>
      <div class="mt-3">
        <div class="progress-thin">
          <div class="progress-bar bg-info" style="width: <?= $todays_applied_jobs > 0 ? 100 : 0 ?>%"></div>
        </div>
        <small class="text-muted">Your daily activity</small>
      </div>
    </div>
  </div>
</div>

<!-- Last 7 Days Applied Jobs -->
<div class="col-md-4 mb-4">
  <div class="card card-student h-100 border-start border-purple border-4">
    <div class="card-body">
      <div class="d-flex justify-content-between align-items-start">
        <div>
          <h6 class="text-uppercase text-muted mb-2">Last 7 Days</h6>
          <h2 class="mb-0 text-purple"><?= $last7_applied_jobs; ?></h2>
        </div>
        <div class="icon-circle bg-purple-light">
          <i class="fas fa-calendar-week text-purple"></i>
        </div>
      </div>
      <div class="mt-3">
        <div class="progress-thin">
          <div class="progress-bar bg-purple" style="width: <?= $last7_applied_jobs > 0 ? 100 : 0 ?>%"></div>
        </div>
        <small class="text-muted">Weekly activity</small>
      </div>
    </div>
  </div>
</div>
      </div>
    </main>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
