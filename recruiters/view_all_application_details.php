<?php
include '../config/db.php';
session_start();

if (!isset($_SESSION['recruiter_id'])) {
    header("Location: recruiter_login.php");
    exit();
}

if (!isset($_GET['application_id'])) {
    die("Application ID is missing.");
}

$recruiter_id = $_SESSION['recruiter_id'];
$application_id = $_GET['application_id'];

// SQL to fetch all application details
$query = "SELECT a.id AS application_id, a.apply_date, a.status,
                 j.job_title, r.company_name, j.created_at AS job_posting_date,
                 s.name AS name, s.email, s.phone_number, s.unique_id, s.gender, s.address, s.age, s.dob,
                 s.tenth_board, s.tenth_year, s.tenth_percentage,
                 s.twelfth_board, s.twelfth_year, s.twelfth_percentage,
                 s.grad_board, s.grad_year, s.grad_percentage,
                 s.postgrad_board, s.postgrad_year, s.postgrad_percentage,
                 a.image_path, a.resume_path
          FROM applications a
          JOIN students s ON a.user_id = s.id
          JOIN jobs j ON a.job_id = j.id
          JOIN recruiters r ON j.recruiter_id = r.id
          WHERE a.id = ? AND r.id = ?";

$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $application_id, $recruiter_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Application details not found.");
}

$row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Application Details</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
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
                    <a class="sidebar-link" href="dashboard.php">
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
                    <a class="sidebar-link active" href="all_applications.php">
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
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-header bg-dark text-white py-3">
            <h2 class="h4 mb-0 text-center"><i class="fas fa-file-alt me-2"></i>Application Details</h2>
        </div>
        
        <div class="card-body">
            <!-- Job Title & Company Name -->
            <div class="text-center mb-4 p-3 bg-light rounded">
                <h3 class="text-primary mb-2"><?= htmlspecialchars($row['job_title']); ?></h3>
                <h5 class="text-muted"><i class="fas fa-building me-2"></i><?= htmlspecialchars($row['company_name']); ?></h5>
            </div>

            <!-- User Details Table -->
            <div class="mb-4">
                <h4 class="mb-3 border-bottom pb-2"><i class="fas fa-user-circle me-2"></i>Personal Information</h4>
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead class="table-light">
                            <tr><th colspan="4" class="bg-light text-dark">Candidate Details</th></tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th width="20%" class="bg-light">Full Name</th>
                                <td width="30%"><?= htmlspecialchars($row['name']); ?></td>
                                <th width="20%" class="bg-light">Email</th>
                                <td width="30%"><?= htmlspecialchars($row['email']); ?></td>
                            </tr>
                            <tr>
                                <th class="bg-light">Mobile Number</th>
                                <td><?= htmlspecialchars($row['phone_number']); ?></td>
                                <th class="bg-light">Student ID</th>
                                <td><?= htmlspecialchars($row['unique_id']); ?></td>
                            </tr>
                            <tr>
                                <th class="bg-light">Gender</th>
                                <td><?= htmlspecialchars($row['gender']); ?></td>
                                <th class="bg-light">Address</th>
                                <td><?= htmlspecialchars($row['address']); ?></td>
                            </tr>
                            <tr>
                                <th class="bg-light">Age</th>
                                <td><?= htmlspecialchars($row['age']); ?></td>
                                <th class="bg-light">Date of Birth</th>
                                <td><?= htmlspecialchars($row['dob']); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Education Details Table -->
            <div class="mb-4">
                <h4 class="mb-3 border-bottom pb-2"><i class="fas fa-graduation-cap me-2"></i>Education Details</h4>
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th class="bg-light">Level</th>
                                <th class="bg-light">Board/University</th>
                                <th class="bg-light">Year</th>
                                <th class="bg-light">Percentage</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>10th</strong></td>
                                <td><?= htmlspecialchars($row['tenth_board']); ?></td>
                                <td><?= htmlspecialchars($row['tenth_year']); ?></td>
                                <td><?= htmlspecialchars($row['tenth_percentage']); ?>%</td>
                            </tr>
                            <tr>
                                <td><strong>12th</strong></td>
                                <td><?= htmlspecialchars($row['twelfth_board']); ?></td>
                                <td><?= htmlspecialchars($row['twelfth_year']); ?></td>
                                <td><?= htmlspecialchars($row['twelfth_percentage']); ?>%</td>
                            </tr>
                            <tr>
                                <td><strong>Graduation</strong></td>
                                <td><?= htmlspecialchars($row['grad_board']); ?></td>
                                <td><?= htmlspecialchars($row['grad_year']); ?></td>
                                <td><?= htmlspecialchars($row['grad_percentage']); ?>%</td>
                            </tr>
                            <?php if (!empty($row['postgrad_board'])) { ?>
                            <tr>
                                <td><strong>Post Graduation</strong></td>
                                <td><?= htmlspecialchars($row['postgrad_board']); ?></td>
                                <td><?= htmlspecialchars($row['postgrad_year']); ?></td>
                                <td><?= htmlspecialchars($row['postgrad_percentage']); ?>%</td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Profile Image & Resume -->
            <div>
                <h4 class="mb-3 border-bottom pb-2"><i class="fas fa-file-upload me-2"></i>Documents</h4>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th width="20%" class="bg-light">Profile Image</th>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="../uploads/<?= htmlspecialchars($row['image_path']); ?>" 
                                             alt="Profile Image" class="img-thumbnail me-3" style="max-width: 120px;">
                                        <a href="../uploads/<?= htmlspecialchars($row['image_path']); ?>" 
                                           download class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-download me-1"></i>Download
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light">Resume</th>
                                <td>
                                    <a href="../uploads/<?= htmlspecialchars($row['resume_path']); ?>" 
                                       download class="btn btn-primary btn-sm">
                                        <i class="fas fa-file-pdf me-1"></i>Download Resume
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>