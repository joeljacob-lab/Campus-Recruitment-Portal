<?php
session_start();
include '../config/db.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id']) || $_SESSION['role'] != 'admin') {
    header("Location: admin_login.php");
    exit();
}

$recruiters = [];
$error = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $from_date = $_POST['from_date'];
    $to_date = $_POST['to_date'];

    // Validate dates
    if ($from_date > $to_date) {
        $error = "From Date cannot be greater than To Date.";
    } else {
        // Fetch recruiters registered within the selected date range
        $query = "SELECT * FROM recruiters WHERE DATE(created_at) BETWEEN ? AND ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('ss', $from_date, $to_date);
        $stmt->execute();
        $result = $stmt->get_result();
        $recruiters = $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Recruiter Reports</title>
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
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
      <!-- Sidebar -->
      <nav class="col-md-3 col-lg-2 d-md-block bg-dark sidebar" style="height: 100vh; position: fixed; width: 225px; overflow-y: auto;">
      <div class="position-sticky pt-3">
        <div class="text-center my-4">
        <img src="../assets/images/admin-icon2.png" alt="Admin" class="rounded-circle" width="80" height="80" />
          <h5 class="mt-2 text-white">Admin</h5>
          <p class="text-success">● Online</p>
            </div>
            <ul class="nav flex-column">
                <li class="sidebar-item">
                    <a class="sidebar-link" href="admin_profile.php">
                        <i class="fas fa-user"></i>
                        <span>Profile</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="admin_setting.php">
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
                    <a class="sidebar-link" href="total_regcompany.php">
                        <i class="fas fa-city"></i>
                        <span>Total Registered Companies</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="total_regusers.php">
                        <i class="fas fa-users"></i>
                        <span>Total Registered Users</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link active" href="reports.php">
                        <i class="fas fa-users"></i>
                        <span>Reports</span>
                    </a>
                </li>
            </ul>
        </div>
      </nav>
    </div> 


    <!--Main Content-->
    <div class="col-md-9 ms-sm-auto col-lg-10 px-md-4" style="margin-left: 18rem; margin-top: 2rem;">
        <h2 class="mb-4">Recruiter Registration Report</h2>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <!-- Date Range Form -->
        <form method="POST" class="row g-3 mb-4">
            <div class="col-md-4">
                <label for="from_date" class="form-label">From Date:</label>
                <input type="date" name="from_date" id="from_date" class="form-control" required
                       value="<?php echo isset($_POST['from_date']) ? $_POST['from_date'] : ''; ?>">
            </div>
            <div class="col-md-4">
                <label for="to_date" class="form-label">To Date:</label>
                <input type="date" name="to_date" id="to_date" class="form-control" required
                       value="<?php echo isset($_POST['to_date']) ? $_POST['to_date'] : ''; ?>">
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary">Generate Report</button>
            </div>
        </form>

        <?php if ($_SERVER['REQUEST_METHOD'] == 'POST' && empty($error)) : ?>
            <h4 class="mb-3">Recruiters registered between <strong><?php echo $from_date; ?></strong> and <strong><?php echo $to_date; ?></strong></h4>

            <?php if (count($recruiters) > 0): ?>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Company Name</th>
                                <th>Recruiter Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Registered On</th>
                                <th>Report</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recruiters as $recruiter): ?>
                                <tr>
                                    <td><?php echo $recruiter['id']; ?></td>
                                    <td><?php echo htmlspecialchars($recruiter['company_name']); ?></td>
                                    <td><?php echo htmlspecialchars($recruiter['name']); ?></td>
                                    <td><?php echo htmlspecialchars($recruiter['email']); ?></td>
                                    <td><?php echo htmlspecialchars($recruiter['phone']); ?></td>
                                    <td><?php echo date('d-m-Y', strtotime($recruiter['created_at'])); ?></td>
                                    <td>
                                        <a href="generate_report.php?id=<?php echo $recruiter['id']; ?>" class="btn btn-success btn-sm" target="_blank">Download</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="alert alert-info">No recruiters found in the selected date range.</div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</body>
</html>
