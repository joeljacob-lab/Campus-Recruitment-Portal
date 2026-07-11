<?php
include '../config/db.php';
session_start();
if (!isset($_SESSION['recruiter_id']) || $_SESSION['role'] != 'recruiter') {
    header("Location: recruiter_login.php");
    exit();
}

$recruiter_id = $_SESSION['recruiter_id']; 

// Total Vacancies Posted by this recruiter
$total_vacancy_query = mysqli_query($conn, "SELECT COUNT(*) AS total FROM jobs WHERE recruiter_id = '$recruiter_id'");
$total_vacancy = mysqli_fetch_assoc($total_vacancy_query)['total'];

// Total Applications received for this recruiter's jobs
$total_app_query = mysqli_query($conn, "
  SELECT COUNT(*) AS total 
  FROM applications a 
  JOIN jobs j ON a.job_id = j.id 
  WHERE j.recruiter_id = '$recruiter_id'
");
$total_applications = mysqli_fetch_assoc($total_app_query)['total'];

// Total New Applications (today)
$new_app_query = mysqli_query($conn, "
  SELECT COUNT(*) AS total 
  FROM applications a 
  JOIN jobs j ON a.job_id = j.id 
  WHERE j.recruiter_id = '$recruiter_id' 
    AND a.apply_date = CURDATE()
");
$new_applications = mysqli_fetch_assoc($new_app_query)['total'];

// Total Selected Applications
$selected_app_query = mysqli_query($conn, "
  SELECT COUNT(*) AS total 
  FROM applications a 
  JOIN jobs j ON a.job_id = j.id 
  WHERE j.recruiter_id = '$recruiter_id' 
    AND a.status = 'shortlisted'
");
$selected_applications = mysqli_fetch_assoc($selected_app_query)['total'];

// Total Rejected Applications
$rejected_app_query = mysqli_query($conn, "
  SELECT COUNT(*) AS total 
  FROM applications a 
  JOIN jobs j ON a.job_id = j.id 
  WHERE j.recruiter_id = '$recruiter_id' 
    AND a.status = 'rejected'
");
$rejected_applications = mysqli_fetch_assoc($rejected_app_query)['total'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recruiter Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
/* Card Styling */
.card {
  border: none;
  border-radius: 12px;
  box-shadow: 0 6px 15px rgba(0, 0, 0, 0.05);
  transition: all 0.3s ease;
  overflow: hidden;
  background: white;
}

.card-hover:hover {
  transform: translateY(-5px);
  box-shadow: 0 12px 20px rgba(0, 0, 0, 0.1);
}

.icon-shape {
  display: inline-flex;
  align-items: center;
  justify-content: center;
}

.text-gradient {
  background-clip: text;
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  position: relative;
  z-index: 1;
}

.text-gradient.text-primary {
  background-image: linear-gradient(310deg, #2152ff, #21d4fd);
}

.text-gradient.text-success {
  background-image: linear-gradient(310deg, #17ad37, #98ec2d);
}

.text-gradient.text-info {
  background-image: linear-gradient(310deg, #2152ff, #a8b8d8);
}

.text-gradient.text-warning {
  background-image: linear-gradient(310deg, #f53939, #fbcf33);
}

.text-gradient.text-danger {
  background-image: linear-gradient(310deg, #ea0606, #ff667c);
}

.bg-primary-light {
  background-color: rgba(33, 82, 255, 0.1);
}

.bg-success-light {
  background-color: rgba(23, 173, 55, 0.1);
}

.bg-info-light {
  background-color: rgba(33, 82, 255, 0.1);
}

.bg-warning-light {
  background-color: rgba(245, 57, 57, 0.1);
}

.bg-danger-light {
  background-color: rgba(234, 6, 6, 0.1);
}

.progress {
  height: 6px;
  border-radius: 3px;
  background-color: #f0f2f5;
}

.progress-bar {
  border-radius: 3px;
}

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
</style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
      <!-- Sidebar -->
      <nav class="col-md-3 col-lg-2 d-md-block bg-dark sidebar" style="height: 100vh; position: fixed; width: 225px; overflow-y: auto;">
      <div class="position-sticky pt-3">
        <div class="text-center my-4">
        <img src="../assets/images/recruiter-icon" alt="Student" class="rounded-circle" width="80" height="80" />
          <h5 class="mt-2 text-white">Recruiter</h5>
          <p class="text-success">● Online</p>
            </div>
            <ul class="nav flex-column">
                <li class="sidebar-item">
                    <a class="sidebar-link" href="recruiter_profile.php">
                        <i class="fas fa-user"></i>
                        <span>Profile</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="recruiter_settings.php">
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
                    <a class="sidebar-link" href="post_vacancy.php">
                        <i class="fas fa-bullhorn"></i>
                        <span>Post Vacancy</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="manage_vacancy.php">
                        <i class="fas fa-history"></i>
                        <span>Manage Vacacnies</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="new_applications.php">
                        <i class="fas fa-file-import"></i>
                        <span>New Applications</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="shortlisted_applications.php">
                        <i class="fas fa-clipboard-check"></i>
                        <span>Shortlisted Applications</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="all_applications.php">
                        <i class="fas fa-list-ol"></i>
                        <span>All Applications</span>
                    </a>
                </li>
            </ul>
        </div>
      </nav>
    </div>  
          
     <!-- Main Content -->
     <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4" style="margin-left: 18rem; margin-top: 2rem;">
      <h2>Recruiter Dashboard</h2>
      <div class="row my-4">
        <!-- Total Vacancies Posted -->
<div class="col-md-4 mb-4">
  <div class="card card-hover h-100" style="border: 1px solid #e0e0e0; border-top: 2px solid #2152ff;">
    <div class="card-body">
      <div class="d-flex justify-content-between align-items-center">
        <div>
          <span class="text-uppercase text-xs text-primary mb-1 d-block">Total Vacancy Posted</span>
          <h2 class="mb-0 text-gradient text-primary"><?= $total_vacancy ?></h2>
        </div>
        <div class="icon icon-shape bg-primary-light rounded-circle p-3">
          <i class="fas fa-briefcase text-primary"></i>
        </div>
      </div>
      <div class="mt-3">
        <span class="text-sm text-muted">Since account creation</span>
      </div>
    </div>
  </div>
</div>

<!-- Total Applications -->
<div class="col-md-4 mb-4">
  <div class="card card-hover h-100" style="border: 1px solid #e0e0e0; border-top: 2px solid #2152ff;">
    <div class="card-body">
      <div class="d-flex justify-content-between align-items-center">
        <div>
          <span class="text-uppercase text-xs text-success mb-1 d-block">Total Applications</span>
          <h2 class="mb-0 text-gradient text-success"><?= $total_applications ?></h2>
        </div>
        <div class="icon icon-shape bg-success-light rounded-circle p-3">
          <i class="fas fa-file-alt text-success"></i>
        </div>
      </div>
      <div class="mt-3">
        <span class="text-sm text-muted">All time applications</span>
      </div>
    </div>
  </div>
</div>

<!-- New Applications Today -->
<div class="col-md-4 mb-4">
  <div class="card card-hover h-100" style="border: 1px solid #e0e0e0; border-top: 2px solid #2152ff;">
    <div class="card-body">
      <div class="d-flex justify-content-between align-items-center">
        <div>
          <span class="text-uppercase text-xs text-info mb-1 d-block">New Applications Today</span>
          <h2 class="mb-0 text-gradient text-info"><?= $new_applications ?></h2>
        </div>
        <div class="icon icon-shape bg-info-light rounded-circle p-3">
          <i class="fas fa-calendar-day text-info"></i>
        </div>
      </div>
      <div class="mt-3">
        <span class="text-sm text-muted">Updated just now</span>
      </div>
    </div>
  </div>
</div>

<!-- Selected Applications -->
<div class="col-md-6 mb-4">
  <div class="card card-hover h-100" style="border: 1px solid #e0e0e0; border-top: 2px solid #2152ff;">
    <div class="card-body">
      <div class="d-flex justify-content-between align-items-center">
        <div>
          <span class="text-uppercase text-xs text-warning mb-1 d-block">Selected Applications</span>
          <h2 class="mb-0 text-gradient text-warning"><?= $selected_applications ?></h2>
        </div>
        <div class="icon icon-shape bg-warning-light rounded-circle p-3">
          <i class="fas fa-user-check text-warning"></i>
        </div>
      </div>
      <div class="mt-3">
        <div class="progress progress-sm">
          <div class="progress-bar bg-warning" style="width: <?= $total_applications > 0 ? ($selected_applications/$total_applications)*100 : 0 ?>%"></div>
        </div>
        <span class="text-sm text-muted"><?= $total_applications > 0 ? round(($selected_applications/$total_applications)*100, 2) : 0 ?>% of total</span>
      </div>
    </div>
  </div>
</div>

<!-- Rejected Applications -->
<div class="col-md-6 mb-4">
  <div class="card card-hover h-100" style="border: 1px solid #e0e0e0; border-top: 2px solid #2152ff;">
    <div class="card-body">
      <div class="d-flex justify-content-between align-items-center">
        <div>
          <span class="text-uppercase text-xs text-danger mb-1 d-block">Rejected Applications</span>
          <h2 class="mb-0 text-gradient text-danger"><?= $rejected_applications ?></h2>
        </div>
        <div class="icon icon-shape bg-danger-light rounded-circle p-3">
          <i class="fas fa-user-times text-danger"></i>
        </div>
      </div>
      <div class="mt-3">
        <div class="progress progress-sm">
          <div class="progress-bar bg-danger" style="width: <?= $total_applications > 0 ? ($rejected_applications/$total_applications)*100 : 0 ?>%"></div>
        </div>
        <span class="text-sm text-muted"><?= $total_applications > 0 ? round(($rejected_applications/$total_applications)*100, 2) : 0 ?>% of total</span>
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

</body>
</html>
