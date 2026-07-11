<?php
session_start();
include '../config/db.php';

// Get user_id from session
$user_id = $_SESSION['user_id'];

// Check if data exists and if any values are non-null or non-zero
$query = "SELECT * FROM students WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

// Check if all values are empty or zero
$isEmpty = (
    empty($data['tenth_board']) && $data['tenth_year'] == 0 && $data['tenth_percentage'] == 0.00 &&
    empty($data['twelfth_board']) && $data['twelfth_year'] == 0 && $data['twelfth_percentage'] == 0.00 &&
    empty($data['grad_board']) && $data['grad_year'] == 0 && $data['grad_percentage'] == 0.00
);

// Form Submission Logic
if (isset($_POST['submit'])) {
    $tenth_board = $_POST['tenth_board'];
    $tenth_year = $_POST['tenth_year'];
    $tenth_percentage = $_POST['tenth_percentage'];
    $twelfth_board = $_POST['twelfth_board'];
    $twelfth_year = $_POST['twelfth_year'];
    $twelfth_percentage = $_POST['twelfth_percentage'];
    $grad_board = $_POST['grad_board'];
    $grad_year = $_POST['grad_year'];
    $grad_percentage = $_POST['grad_percentage'];
    $postgrad_board = !empty($_POST['postgrad_board']) ? $_POST['postgrad_board'] : NULL;
    $postgrad_year = !empty($_POST['postgrad_year']) ? $_POST['postgrad_year'] : NULL;
    $postgrad_percentage = !empty($_POST['postgrad_percentage']) ? $_POST['postgrad_percentage'] : NULL;

    // Update the data
    $query = "UPDATE students SET tenth_board=?, tenth_year=?, tenth_percentage=?, 
              twelfth_board=?, twelfth_year=?, twelfth_percentage=?, 
              grad_board=?, grad_year=?, grad_percentage=?, postgrad_board=?, postgrad_year=?, postgrad_percentage=? WHERE id=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssdsdsdsdssdi", $tenth_board, $tenth_year, $tenth_percentage,
                      $twelfth_board, $twelfth_year, $twelfth_percentage,
                      $grad_board, $grad_year, $grad_percentage,
                      $postgrad_board, $postgrad_year, $postgrad_percentage, $user_id);

    if ($stmt->execute()) {
        echo "<script>alert('Educational details added successfully!'); window.location='education_form.php';</script>";
    } else {
        echo "<script>alert('Error adding educational details: " . $stmt->error . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Educational Details</title>
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

        /* Form Specific Styles (scoped with form- prefix) */
        :root {
            --form-primary: #4361ee;
            --form-primary-light: #4895ef;
            --form-secondary: #3f37c9;
            --form-dark: #1b263b;
            --form-light: #f8f9fa;
            --form-success: #4cc9f0;
            --form-border-radius: 8px;
            --form-shadow: 0 10px 20px rgba(0,0,0,0.1);
            --form-transition: all 0.3s ease;
        }

        .form-main-content {
            margin-left: 280px;
            width: calc(100% - 280px);
            padding: 40px;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }

        .form-container {
            width: 100%;
            max-width: 800px;
            background: white;
            border-radius: var(--form-border-radius);
            box-shadow: var(--form-shadow);
            overflow: hidden;
            transform: translateY(0);
            transition: var(--form-transition);
        }

        .form-container:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.15);
        }

        .form-header {
            background: linear-gradient(to right, var(--form-primary), var(--form-secondary));
            color: white;
            padding: 20px 30px;
            text-align: center;
        }

        .form-header h2 {
            font-weight: 600;
            letter-spacing: 1px;
        }

        .form-body {
            padding: 30px;
        }

        .form-section {
            margin-bottom: 25px;
            animation: form-fadeIn 0.5s ease forwards;
        }

        @keyframes form-fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .form-section-title {
            font-size: 1.1rem;
            color: var(--form-primary);
            margin-bottom: 15px;
            padding-bottom: 5px;
            border-bottom: 2px solid #eee;
            display: flex;
            align-items: center;
        }

        .form-section-title::before {
            content: "▹";
            margin-right: 10px;
            color: var(--form-primary-light);
        }

        .form-row {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -10px 15px;
        }

        .form-group {
            flex: 1 0 200px;
            margin: 0 10px 15px;
            position: relative;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-size: 0.9rem;
            color: var(--form-dark);
            font-weight: 500;
        }

        .form-input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e9ecef;
            border-radius: var(--form-border-radius);
            font-family: 'Poppins', sans-serif;
            font-size: 0.95rem;
            transition: var(--form-transition);
            background-color: #f8f9fa;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--form-primary-light);
            background-color: white;
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.2);
        }

        .form-input::placeholder {
            color: #adb5bd;
        }

        .form-optional {
            font-size: 0.7rem;
            background: #e9ecef;
            color: #6c757d;
            padding: 2px 5px;
            border-radius: 4px;
            margin-left: 5px;
        }

        .form-submit {
            background: linear-gradient(to right, var(--form-primary), var(--form-secondary));
            color: white;
            border: none;
            padding: 12px 30px;
            font-size: 1rem;
            font-weight: 500;
            border-radius: 50px;
            cursor: pointer;
            transition: var(--form-transition);
            display: block;
            margin: 30px auto 0;
            box-shadow: 0 4px 15px rgba(67, 97, 238, 0.3);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .form-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 7px 20px rgba(67, 97, 238, 0.4);
        }

        /* Responsive adjustments */
        @media (max-width: 992px) {
            .sidebar-container {
                width: 70px;
                overflow: hidden;
            }
            
            .sidebar-header {
                padding: 20px 10px;
            }
            
            .sidebar-avatar {
                width: 40px;
                height: 40px;
            }
            
            .sidebar-username, .sidebar-status, .sidebar-link span {
                display: none;
            }
            
            .sidebar-link {
                justify-content: center;
                padding: 15px 10px;
            }
            
            .sidebar-link i {
                margin-right: 0;
                font-size: 1.3rem;
            }

            .form-main-content {
                margin-left: 70px;
                width: calc(100% - 70px);
                padding: 20px;
            }
        }

        @media (max-width: 768px) {
            .form-group {
                flex: 1 0 100%;
            }
        }

/* Table Styling */
.education-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    margin: 25px 0;
}

.education-table thead {
    background: linear-gradient(135deg, #4361ee, #3f37c9);
    color: white;
}

.education-table th {
    padding: 16px 12px;
    text-align: left;
    font-weight: 600;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    font-size: 0.85rem;
}

.education-table tbody tr {
    transition: all 0.2s ease;
}

.education-table tbody tr:nth-child(even) {
    background-color: #f8f9fa;
}

.education-table tbody tr:nth-child(odd) {
    background-color: white;
}

.education-table tbody tr:hover {
    background-color: #e9f5ff;
    transform: translateX(2px);
}

.education-table td {
    padding: 14px 12px;
    border-bottom: 1px solid #e0e0e0;
    color: #333;
}

.education-table td:first-child {
    font-weight: 500;
    color: #2c3e50;
}

.education-table .highlight-cell {
    font-weight: 600;
    color: #4361ee;
}

/* Status indicator for Post Grad */
.education-table .na-cell {
    color: #6c757d;
    font-style: italic;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .education-table {
        display: block;
        overflow-x: auto;
    }
}        
      </style>
</head>
<body>
<div class="container-fluid">
  <div class="row">
    <!-- Sidebar (unchanged style, just proper Bootstrap columns) -->
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
                    <a class="sidebar-link active" href="education_form.php">
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
<?php if ($isEmpty): ?>
    <h3>Enter Your Educational Details</h3>
    <form method="post" class="form-body">
                <!-- 10th Grade Section -->
                <div class="form-section" style="animation-delay: 0.1s">
                    <div class="form-section-title">Secondary Education (10th)</div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label" for="tenth_board">Board</label>
                            <input type="text" id="tenth_board" name="tenth_board" class="form-input" required placeholder="e.g. CBSE, ICSE, State Board">
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="tenth_year">Year of Completion</label>
                            <input type="number" id="tenth_year" name="tenth_year" class="form-input" required placeholder="e.g. 2015" min="1900" max="2099">
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="tenth_percentage">Percentage</label>
                            <input type="number" step="0.01" id="tenth_percentage" name="tenth_percentage" class="form-input" required placeholder="e.g. 85.50" min="0" max="100">
                        </div>
                    </div>
                </div>
                
                <!-- 12th Grade Section -->
                <div class="form-section" style="animation-delay: 0.2s">
                    <div class="form-section-title">Higher Secondary (12th)</div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label" for="twelfth_board">Board</label>
                            <input type="text" id="twelfth_board" name="twelfth_board" class="form-input" required placeholder="e.g. CBSE, State Board">
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="twelfth_year">Year of Completion</label>
                            <input type="number" id="twelfth_year" name="twelfth_year" class="form-input" required placeholder="e.g. 2017" min="1900" max="2099">
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="twelfth_percentage">Percentage</label>
                            <input type="number" step="0.01" id="twelfth_percentage" name="twelfth_percentage" class="form-input" required placeholder="e.g. 78.25" min="0" max="100">
                        </div>
                    </div>
                </div>
                
                <!-- Graduation Section -->
                <div class="form-section" style="animation-delay: 0.3s">
                    <div class="form-section-title">Graduation</div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label" for="grad_board">College/University</label>
                            <input type="text" id="grad_board" name="grad_board" class="form-input" required placeholder="e.g. University of Delhi">
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="grad_year">Year of Completion</label>
                            <input type="number" id="grad_year" name="grad_year" class="form-input" required placeholder="e.g. 2020" min="1900" max="2099">
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="grad_percentage">Percentage/CGPA</label>
                            <input type="number" step="0.01" id="grad_percentage" name="grad_percentage" class="form-input" required placeholder="e.g. 75.50 or 8.5" min="0" max="100">
                        </div>
                    </div>
                </div>
                
                <!-- Post Graduation Section -->
                <div class="form-section" style="animation-delay: 0.4s">
                    <div class="form-section-title">Post Graduation <span class="form-optional">Optional</span></div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label" for="postgrad_board">College/University</label>
                            <input type="text" id="postgrad_board" name="postgrad_board" class="form-input" placeholder="e.g. IIT Bombay">
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="postgrad_year">Year of Completion</label>
                            <input type="number" id="postgrad_year" name="postgrad_year" class="form-input" placeholder="e.g. 2022" min="1900" max="2099">
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="postgrad_percentage">Percentage/CGPA</label>
                            <input type="number" step="0.01" id="postgrad_percentage" name="postgrad_percentage" class="form-input" placeholder="e.g. 80.00 or 9.0" min="0" max="100">
                        </div>
                    </div>
                </div>
                
                <button type="submit" name="submit" class="form-submit">Submit Details</button>
            </form>
<?php else: ?>
    <h3>Your Educational Details</h3>
    <table class="education-table">
    <thead>
        <tr>
            <th>Education Level</th>
            <th>Board / University</th>
            <th>Year</th>
            <th>Percentage</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td class="highlight-cell">10th (Secondary)</td>
            <td><?= htmlspecialchars($data['tenth_board']); ?></td>
            <td><?= htmlspecialchars($data['tenth_year']); ?></td>
            <td><?= htmlspecialchars($data['tenth_percentage']); ?>%</td>
        </tr>
        <tr>
            <td class="highlight-cell">12th (Senior Secondary)</td>
            <td><?= htmlspecialchars($data['twelfth_board']); ?></td>
            <td><?= htmlspecialchars($data['twelfth_year']); ?></td>
            <td><?= htmlspecialchars($data['twelfth_percentage']); ?>%</td>
        </tr>
        <tr>
            <td class="highlight-cell">Graduation</td>
            <td><?= htmlspecialchars($data['grad_board']); ?></td>
            <td><?= htmlspecialchars($data['grad_year']); ?></td>
            <td><?= htmlspecialchars($data['grad_percentage']); ?>%</td>
        </tr>
        <tr>
            <td class="highlight-cell">Post Graduation</td>
            <td class="<?= empty($data['postgrad_board']) ? 'na-cell' : '' ?>">
                <?= htmlspecialchars($data['postgrad_board'] ?? 'NA'); ?>
            </td>
            <td class="<?= empty($data['postgrad_year']) ? 'na-cell' : '' ?>">
                <?= htmlspecialchars($data['postgrad_year'] ?? 'NA'); ?>
            </td>
            <td class="<?= empty($data['postgrad_percentage']) ? 'na-cell' : '' ?>">
                <?= htmlspecialchars($data['postgrad_percentage'] ?? 'NA'); ?>
            </td>
        </tr>
    </tbody>
</table>
</main>
  <?php endif; ?>
</div>
</body>
</html>
