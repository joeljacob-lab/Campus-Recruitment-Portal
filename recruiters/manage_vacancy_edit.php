<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['recruiter_id'])) {
    header("Location: login.php");
    exit();
}

$recruiter_id = $_SESSION['recruiter_id'];

// Check if job ID is passed in the URL
if (!isset($_GET['id'])) { // Changed from job_id to id
    die("Job ID is missing.");
}

$job_id = $_GET['id']; // Fetch job ID from URL

// Fetch job details from the database
$query = "SELECT * FROM jobs WHERE id = ? AND recruiter_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $job_id, $recruiter_id);
$stmt->execute();
$result = $stmt->get_result();
$job = $result->fetch_assoc();

if (!$job) {
    die("Job not found or you don't have permission to edit this job.");
}

// If the form is submitted, update the job details
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $job_title = $_POST['job_title'];
    $job_description = $_POST['job_description'];
    $job_type = $_POST['job_type'];
    $job_location = $_POST['job_location'];
    $number_openings = $_POST['number_openings'];
    $job_salary = $_POST['job_salary'];
    $skills_required = $_POST['skills_required'];
    $apply_date = $_POST['apply_date'];
    $last_date = $_POST['last_date'];

    $update_query = "UPDATE jobs SET job_title=?, job_description=?, job_type=?, job_location=?, number_openings=?, job_salary=?, skills_required=?, apply_date=?, last_date=? WHERE id=? AND recruiter_id=?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("ssssissssii", $job_title, $job_description, $job_type, $job_location, $number_openings, $job_salary, $skills_required, $apply_date, $last_date, $job_id, $recruiter_id);

    if ($stmt->execute()) {
        echo "<script>alert('Vacancy updated successfully!'); window.location.href='manage_vacancy.php';</script>";
    } else {
        echo "Error updating vacancy.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Vacancy</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
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
                    <a class="sidebar-link active" href="manage_vacancy.php">
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
        <h2>Edit Vacancy</h2>
        <form method="POST" class="w-50">
        <div class="mb-3">
            <label class="form-label">Job Title</label>
            <input type="text" class="form-control" name="job_title" value="<?php echo htmlspecialchars($job['job_title']); ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea class="form-control" name="job_description" rows="3" required><?php echo htmlspecialchars($job['job_description']); ?></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Job Type</label>
            <select class="form-select" name="job_type" required>
                <option value="Full-time" <?php if($job['job_type'] == "Full-time") echo "selected"; ?>>Full-time</option>
                <option value="Part-time" <?php if($job['job_type'] == "Part-time") echo "selected"; ?>>Part-time</option>
                <option value="Internship" <?php if($job['job_type'] == "Internship") echo "selected"; ?>>Internship</option>
                <option value="Contract" <?php if($job['job_type'] == "Contract") echo "selected"; ?>>Contract</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Location</label>
            <input type="text" class="form-control" name="job_location" value="<?php echo htmlspecialchars($job['job_location']); ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Number of Openings</label>
            <input type="number" class="form-control" name="number_openings" value="<?php echo htmlspecialchars($job['number_openings']); ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Salary</label>
            <input type="number" class="form-control" name="job_salary" value="<?php echo htmlspecialchars($job['job_salary']); ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Requirements</label>
            <textarea class="form-control" name="skills_required" rows="3" required><?php echo htmlspecialchars($job['skills_required']); ?></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Apply Date</label>
            <input type="date" class="form-control" name="apply_date" value="<?php echo htmlspecialchars($job['apply_date']); ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Last Date</label>
            <input type="date" class="form-control" name="last_date" value="<?php echo htmlspecialchars($job['last_date']); ?>" required>
        </div>

            <button type="submit" class="btn btn-primary">Update Vacancy</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
