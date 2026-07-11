<?php
session_start();
include '../config/db.php';

// Check if student is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: student_login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch student details
$stmt = $conn->prepare("SELECT tenth_board, tenth_year, tenth_percentage, twelfth_board, twelfth_year, twelfth_percentage, grad_board, grad_year, grad_percentage, postgrad_board, postgrad_year, postgrad_percentage FROM students WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();
$stmt->close();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and fetch values
    $edu_levels = ['tenth', 'twelfth', 'grad', 'postgrad'];
    foreach ($edu_levels as $level) {
        $board = $_POST[strtolower(str_replace(' ', '_', $level)) . '_board'];
        $year = $_POST[strtolower(str_replace(' ', '_', $level)) . '_year'];
        $percentage = $_POST[strtolower(str_replace(' ', '_', $level)) . '_percentage'];

        // Update database
        $stmt = $conn->prepare("UPDATE students SET ".strtolower(str_replace(' ', '_', $level))."_board = ?, ".strtolower(str_replace(' ', '_', $level))."_year = ?, ".strtolower(str_replace(' ', '_', $level))."_percentage = ? WHERE id = ?");
        $stmt->bind_param("ssdi", $board, $year, $percentage, $user_id);
        $stmt->execute();
        
    }
   
    if ($stmt->execute()) {
        $success = "Education details updated successfully.";
        echo "<script>
            alert('$success');
            window.location.href = 'dashboard.php';
        </script>";
        exit();
    }
    
    
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Manage Education Details</title>
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

/* Modern Glass Form Design */
.glass-form {
    max-width: 800px;
    margin: 2rem auto;
    padding: 2.5rem;
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(10px);
    border-radius: 16px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    font-family: 'Segoe UI', system-ui, sans-serif;
}

.section-title {
    color: #1a365d;
    margin: 1.5rem 0 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid rgba(26, 54, 93, 0.1);
    font-weight: 600;
    font-size: 1.25rem;
    position: relative;
}

.section-title:after {
    content: '';
    position: absolute;
    left: 0;
    bottom: -2px;
    width: 50px;
    height: 2px;
    background: #4299e1;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-label {
    display: block;
    margin-bottom: 0.5rem;
    color: #4a5568;
    font-weight: 500;
    font-size: 0.95rem;
}

.form-input {
    width: 100%;
    padding: 0.875rem 1rem;
    border: 1px solid rgba(203, 213, 224, 0.7);
    border-radius: 8px;
    background: rgba(247, 250, 252, 0.7);
    transition: all 0.3s ease;
    font-size: 1rem;
    color: #2d3748;
}

.form-input:focus {
    outline: none;
    border-color: #4299e1;
    box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.2);
    background: white;
}

.divider {
    height: 1px;
    background: linear-gradient(to right, transparent, rgba(203, 213, 224, 0.5), transparent);
    margin: 2rem 0;
}

.submit-btn {
    background: linear-gradient(135deg, #4299e1, #3182ce);
    color: white;
    border: none;
    padding: 0.875rem 2rem;
    border-radius: 8px;
    font-weight: 500;
    font-size: 1rem;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-block;
    box-shadow: 0 4px 6px rgba(66, 153, 225, 0.2);
}

.submit-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 12px rgba(66, 153, 225, 0.25);
}

.submit-btn:active {
    transform: translateY(0);
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .glass-form {
        padding: 1.5rem;
        margin: 1rem;
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
                    <a class="sidebar-link" href="dashboard.php">
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
                    <a class="sidebar-link active" href="manage_education_form.php">
                        <i class="fas fa-tasks"></i>
                        <span>Manage Education Form</span>
                    </a>
                </li>
            </ul>
        </div>
      </nav>
    </div>  


     <!-- Main Content -->
     <div class="col-md-9 ms-sm-auto col-lg-10 px-md-4" style="margin-left: 18rem; margin-top: 2rem;">
    <h2>Manage Education Details</h2>
    <?php if (isset($success)) echo '<div class="alert alert-success">' . $success . '</div>'; ?>
    <div class="glass-form">
    <form method="post">
        <?php 
        $levels = ['tenth' => '10th', 'twelfth' => '12th', 'grad' => 'Graduation', 'postgrad' => 'Post Graduation'];
        foreach ($levels as $level => $label) { ?>
            <h3 class="section-title"><?php echo $label; ?> Details</h3>
            <div class="form-group">
                <label class="form-label">Board</label>
                <input type="text" class="form-input" name="<?php echo $level; ?>_board" value="<?php echo htmlspecialchars($student[$level . '_board']); ?>">
            </div>
            <div class="form-group">
                <label class="form-label">Year</label>
                <input type="number" class="form-input" name="<?php echo $level; ?>_year" value="<?php echo htmlspecialchars($student[$level . '_year']); ?>">
            </div>
            <div class="form-group">
                <label class="form-label">Percentage</label>
                <input type="text" class="form-input" name="<?php echo $level; ?>_percentage" value="<?php echo htmlspecialchars($student[$level . '_percentage']); ?>">
            </div>
            <div class="divider"></div>
        <?php } ?>
        <button type="submit" class="submit-btn">Update Education Details</button>
    </form>
</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
