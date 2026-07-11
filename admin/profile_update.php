<?php
session_start();
include '../config/db.php';

// Ensure admin is logged in
if (!isset($_SESSION['admin_id']) || $_SESSION['role'] != 'admin') {
    header("Location: admin_login.php");
    exit();
}

$admin_id = $_SESSION['admin_id'];

// Fetch admin data
$query = "SELECT admin_name, email, username, phone_number FROM admin WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $admin_id);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get data from form
    $admin_name = $_POST['admin_name'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $phone_number = $_POST['phone_number'];

    // Update student information
    $update_query = "UPDATE admin SET admin_name=?, email=?, username=?, phone_number=? WHERE id=?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param('ssssi', $admin_name, $email, $username, $phone_number, $admin_id);

    if ($update_stmt->execute()) {
        echo "<script>alert('Profile updated successfully!'); window.location.href='admin_profile.php';</script>";
    } else {
        echo "<script>alert('Error updating profile. Please try again.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
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


/* Futuristic Form Styling */
.tech-form {
    max-width: 800px;
    margin: 2rem auto;
    padding: 2.5rem;
    background: #0f172a;
    border-radius: 16px;
    border: 1px solid rgba(100, 116, 139, 0.3);
    box-shadow: 0 10px 30px rgba(15, 23, 42, 0.5),
                inset 0 0 0 1px rgba(255, 255, 255, 0.05);
    font-family: 'Segoe UI', 'Roboto', sans-serif;
    position: relative;
    overflow: hidden;
}

.tech-form::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, 
                rgba(56, 182, 255, 0.1) 0%, 
                rgba(56, 182, 255, 0) 70%);
    animation: pulse 15s infinite linear;
    z-index: 0;
}

@keyframes pulse {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.tech-form .mb-3 {
    position: relative;
    margin-bottom: 1.75rem;
    z-index: 1;
}

.tech-form .form-label {
    display: block;
    margin-bottom: 0.75rem;
    color: #94a3b8;
    font-size: 0.9rem;
    font-weight: 500;
    letter-spacing: 0.5px;
    text-transform: uppercase;
}

.tech-form .form-control {
    width: 100%;
    padding: 1rem 1.25rem;
    background: rgba(15, 23, 42, 0.7);
    border: 1px solid #334155;
    border-radius: 8px;
    color: #e2e8f0;
    font-size: 1rem;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    position: relative;
}

.tech-form .form-control:focus {
    outline: none;
    border-color: #38bdf8;
    box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.2),
                inset 0 0 10px rgba(56, 189, 248, 0.1);
    background: rgba(15, 23, 42, 0.9);
}

.tech-form .form-control::placeholder {
    color: #64748b;
}

.tech-form .btn-primary {
    background: linear-gradient(135deg, #38bdf8, #0ea5e9);
    border: none;
    padding: 1rem 2.5rem;
    border-radius: 8px;
    color: white;
    font-weight: 600;
    font-size: 1rem;
    letter-spacing: 0.5px;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(56, 189, 248, 0.3),
                inset 0 0 0 1px rgba(255, 255, 255, 0.1);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    margin-top: 1rem;
    position: relative;
    overflow: hidden;
    z-index: 1;
}

.tech-form .btn-primary::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, 
                transparent, 
                rgba(255, 255, 255, 0.1), 
                transparent);
    transition: 0.5s;
    z-index: -1;
}

.tech-form .btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(56, 189, 248, 0.4),
                inset 0 0 0 1px rgba(255, 255, 255, 0.1);
}

.tech-form .btn-primary:hover::before {
    left: 100%;
}

.tech-form .btn-primary:active {
    transform: translateY(0);
}

/* Responsive Design */
@media (max-width: 768px) {
    .tech-form {
        padding: 1.5rem;
        margin: 1rem;
    }
    
    .tech-form .form-control {
        padding: 0.875rem 1rem;
    }
    
    .tech-form .btn-primary {
        padding: 0.875rem 1.75rem;
        width: 100%;
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
                    <a class="sidebar-link active" href="admin_profile.php">
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
            </ul>
        </div>
      </nav>
    </div> 

     <!-- Main Content -->
     <div class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <h2 class="my-4">Update Profile</h2>
                <div class="tech-form">
    <form method="post" action="">
        <?php foreach ($admin as $key => $value) { ?>
            <div class="mb-3">
                <label class="form-label text-capitalize">
                    <?php echo ucfirst(str_replace('_', ' ', $key)); ?>
                </label>
                <input type="text" 
                       class="form-control" 
                       name="<?php echo $key; ?>" 
                       value="<?php echo htmlspecialchars($value); ?>" 
                       required>
            </div>
        <?php } ?>
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-paper-plane"></i> Submit
        </button>
    </form>
</div>
            </div>    
        </div>
    </div>
</body>
</html>
       