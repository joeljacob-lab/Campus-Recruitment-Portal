<?php echo "FILE UPDATED SUCCESSFULLY"; ?>
<?php include 'config/db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campus Recruitment Portal</title>
    <link rel="stylesheet" href="assets/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        :root {
            --primary-color: #0d6efd;
            --secondary-color: #6c757d;
            --dark-color: #343a40;
            --light-color: #f8f9fa;
            --accent-color: #ff6b6b;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
            color: #333;
            line-height: 1.6;
            padding-top: 60px; /* Added for fixed navbar */
        }
        
        /* Navbar Styles - Reduced height and improved styling */
        header {
            background-color: rgba(36, 35, 35, 0.95) !important;
            backdrop-filter: blur(8px);
            padding: 5px 0 !important; /* Reduced padding */
            transition: all 0.3s ease;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
            z-index: 1030;
            height: 90px; /* Fixed height */
        }
        
        header.scrolled {
            background-color: rgba(45, 45, 45, 0.98) !important;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        }
        
         /* Shift logo and title more to the left */
         .navbar-brand-container {
            margin-right: auto; /* Pushes everything else to the right */
            padding-left: 10px; /* Added left padding */
            transform: translateX(-10px); /* Shifts left */
        }
        
        .navbar-brand {
            display: flex;
            align-items: center;
            height: 50px;
        }
        
        .navbar-brand img {
            height: 32px;
            margin-right: 12px;
            transition: all 0.3s;
        }
        
        .navbar-brand h2 {
            font-size: 1.1rem;
            margin: 0;
            color: white;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        /* Shift nav links more to the right */
        .navbar-collapse {
            margin-right: 15px; /* Added right margin */
            transform: translateX(10px); /* Shifts right */
        }
        
        .nav-link {
            padding: 0.35rem 0.8rem !important;
            font-weight: 500;
            font-size: 1.0rem;
            color: white !important;
            transition: all 0.2s;
            position: relative;
            margin: 0 2px; /* Reduced spacing between items */
        }
        
        .nav-link:hover {
            transform: translateY(-2px);
        }
        
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background-color: white;
            transition: width 0.3s ease;
        }
        
        .nav-link:hover::after {
            width: 100%;
        }
        
         /* Enhanced Dropdown Menu Styling */
         .dropdown-menu {
            border: none;
            border-radius: 6px;
            padding: 0; /* Changed from 5px to 0 for tighter fit */
            margin-top: 8px !important;
            box-shadow: inset 0 0 8px rgba(0, 0, 0, 0.2), /* Inner shadow */
                        0 4px 8px rgba(0, 0, 0, 0.1); /* Subtle outer shadow */
            background-color: rgba(50, 50, 50, 0.98);
            min-width: 180px;
            overflow: hidden; /* Keeps shadow within bounds */
            border: 1px solid rgba(255, 255, 255, 0.1); /* Subtle border */
        }
        
        .dropdown-item {
            color: white !important;
            padding: 8px 16px !important; /* Slightly more padding */
            font-size: 0.86rem;
            transition: all 0.2s ease;
            position: relative;
        }
        
        .dropdown-item:not(:last-child) {
            border-bottom: 1px solid rgba(255, 255, 255, 0.05); /* Subtle divider */
        }
        
        .dropdown-item:hover {
            background-color: rgba(70, 130, 180, 0.3) !important; /* Blue-ish hover */
            padding-left: 20px !important;
            color: #fff !important;
        }
        
        .dropdown-item:hover::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 3px;
            background: var(--primary-color);
        }
        
        .dropdown-divider {
            border-color: rgba(255, 255, 255, 0.1);
            margin: 0; /* Removed margin */
        }
        
        .navbar-toggler {
            border: none;
            padding: 0.25rem 0.5rem;
            margin-right: 10px; /* Adjusted position */
        }
        
        .navbar-toggler:focus {
            box-shadow: none;
        }
        
        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28255, 255, 255, 1%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }
        
        /* Hero Section */
        .hero-banner {
            position: relative;
            height: 90vh;
            min-height: 600px;
            overflow: hidden;
            margin-top: -60px; /* Adjusted for reduced navbar */
            z-index: 1;
        }
        
        .hero-banner::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(13, 110, 253, 0.8) 0%, rgba(108, 117, 125, 0.7) 100%);
            z-index: 2;
        }
        
        .hero-banner img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            transition: transform 10s ease;
        }
        
        .hero-banner:hover img {
            transform: scale(1.05);
        }
        
        .hero-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            color: white;
            z-index: 3;
            width: 80%;
        }
        
        .hero-content h2 {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.3);
            animation: fadeInDown 1s ease;
        }
        
        .hero-content p {
            font-size: 1.5rem;
            max-width: 800px;
            margin: 0 auto 2.5rem;
            text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.3);
            animation: fadeInUp 1s ease;
        }
        
        .btn-hero {
            padding: 12px 35px;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 50px;
            background-color: white;
            color: var(--primary-color);
            border: 2px solid white;
            transition: all 0.4s ease;
            animation: fadeIn 1.5s ease;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .btn-hero:hover {
            background-color: transparent;
            color: white;
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        }
        
        /* About Section */
        .about-section {
            padding: 100px 0;
            background-color: var(--light-color);
            position: relative;
        }
        
        .about-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100px;
            background: linear-gradient(to bottom, rgba(255,255,255,1) 0%, rgba(255,255,255,0) 100%);
            z-index: 1;
        }
        
        .section-title {
            text-align: center;
            margin-bottom: 60px;
            position: relative;
        }
        
        .section-title h2 {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 20px;
        }
        
        .section-title p {
            font-size: 1.2rem;
            color: var(--secondary-color);
            max-width: 700px;
            margin: 0 auto;
        }
        
        .about-content {
            background: white;
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            margin-bottom: 50px;
        }
        
        .about-content p {
            font-size: 1.1rem;
            margin-bottom: 20px;
            color: #555;
        }
        
        .about-content p:last-child {
            margin-bottom: 0;
        }
        
        .feature-box {
            padding: 40px 30px;
            border-radius: 12px;
            background: white;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
            margin-bottom: 30px;
            transition: all 0.4s ease;
            height: 100%;
            border: 1px solid rgba(0,0,0,0.05);
            text-align: center;
        }
        
        .feature-box:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
            border-color: rgba(var(--primary-color), 0.2);
        }
        
        .feature-icon {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 25px;
            display: inline-block;
            width: 80px;
            height: 80px;
            line-height: 80px;
            background: rgba(var(--primary-color), 0.1);
            border-radius: 50%;
            transition: all 0.3s ease;
        }
        
        .feature-box:hover .feature-icon {
            background: var(--primary-color);
            color: white;
            transform: rotateY(180deg);
        }
        
        .feature-box h4 {
            font-weight: 600;
            margin-bottom: 15px;
            color: var(--dark-color);
        }
        
        .feature-box p {
            color: var(--secondary-color);
            font-size: 1rem;
        }
        
        
        /* Stats Counter Animation */
        .stat-number {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 10px;
            color: white;
            display: inline-block;
        }
        
        .stats-section {
            padding: 80px 0;
            background: linear-gradient(135deg, var(--primary-color) 0%, #4a6cf7 100%);
            color: white;
            position: relative;
            overflow: hidden;
        }
        
        /* Footer Section */
        footer {
            background-color: var(--dark-color);
            color: white;
            padding: 80px 0 20px;
            position: relative;
        }
        
        footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 10px;
            background: linear-gradient(90deg, var(--primary-color) 0%, var(--accent-color) 100%);
        }
        
        .footer-links h5 {
            color: #fff;
            margin-bottom: 25px;
            position: relative;
            padding-bottom: 15px;
            font-weight: 600;
        }
        
        .footer-links h5::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 50px;
            height: 3px;
            background: var(--primary-color);
            border-radius: 3px;
        }
        
        .footer-links ul {
            list-style: none;
            padding: 0;
        }
        
        .footer-links li {
            margin-bottom: 12px;
            display: flex;
            align-items: center;
        }
        
        .footer-links i {
            margin-right: 10px;
            color: var(--primary-color);
            width: 20px;
            text-align: center;
        }
        
        .footer-links a {
            color: #adb5bd;
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .footer-links a:hover {
            color: white;
            padding-left: 5px;
        }
        
        .social-icons {
            display: flex;
            margin-top: 20px;
        }
        
        .social-icons a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
            color: white;
            margin-right: 10px;
            transition: all 0.3s;
        }
        
        .social-icons a:hover {
            background: var(--primary-color);
            transform: translateY(-5px);
        }
        
        .copyright {
            border-top: 1px solid rgba(255,255,255,0.1);
            padding-top: 30px;
            margin-top: 50px;
            text-align: center;
        }
        
        /* Header Styles */
        header {
            background-color: rgba(36, 35, 35, 0.66) !important;
            backdrop-filter: blur(5px);
            padding: 10px 0 !important;
            transition: all 0.3s ease;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            z-index: 1030;
        }
        
        header.scrolled {
            background-color: rgba(45, 45, 45, 0.98) !important;
            padding: 5px 0 !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        
        .navbar-brand {
            display: flex;
            align-items: center;
        }
        
        .navbar-brand img {
            height: 40px;
            margin-right: 15px;
        }
        
        .navbar-brand h2 {
            font-size: 1.3rem;
            margin: 0;
            color: white;
        }
        
        .nav-link {
            padding: 0.5rem 1rem !important;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Responsive Adjustments */
        @media (max-width: 992px) {
            .hero-content h2 {
                font-size: 2.8rem;
            }
            
            .hero-content p {
                font-size: 1.2rem;
            }
        }
        
        @media (max-width: 768px) {
            .hero-banner {
                height: 70vh;
                min-height: 500px;
            }
            
            .hero-content h2 {
                font-size: 2.2rem;
            }
            
            .hero-content p {
                font-size: 1rem;
            }
            
            .section-title h2 {
                font-size: 2rem;
            }
            
            .about-content {
                padding: 30px 20px;
            }
        }
        
        @media (max-width: 576px) {
            .hero-banner {
                height: 60vh;
                min-height: 400px;
            }
            
            .navbar-brand h2 {
                font-size: 1.1rem;
            }
            
            .stat-number {
                font-size: 2.5rem;
            }
        }
    </style>
</head>
<body>
<header class="fixed-top">
    <div class="container d-flex justify-content-between align-items-center">
        <!-- Added container div for logo and title -->
        <div class="navbar-brand-container">
            <div class="d-flex align-items-center">
                <img src="assets/images/kdc-logo.jpg" alt="KDC Logo" style="height: 32px; margin-right: 12px;">
                <h2 class="m-0">Fisat Campus Recruitment Portal</h2>
            </div>
        </div>
        <nav class="navbar navbar-expand-lg">
            <div class="container-fluid">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto"> <!-- ms-auto to align right -->
                        <li class="nav-item">
                            <a class="nav-link" href="#about-section">About</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Students</a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="students/student_login.php">Sign In</a></li>
                                <li><div class="dropdown-divider"></div></li>
                                <li><a class="dropdown-item" href="students/student_register.php">Register</a></li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Recruiters</a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="recruiters/recruiter_login.php">Sign In</a></li>
                                <li><div class="dropdown-divider"></div></li>
                                <li><a class="dropdown-item" href="recruiters/recruiter_register.php">Register</a></li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="admin/admin_login.php">Admin</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#footer-section">Contact</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
</header>

<script>
    // Navbar scroll effect (existing code)
    window.addEventListener('scroll', function() {
        const header = document.querySelector('header');
        if (window.scrollY > 50) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
    });

    // Smooth scrolling (existing code)
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            
            const targetId = this.getAttribute('href');
            if(targetId === '#') return;
            
            const targetElement = document.querySelector(targetId);
            if(targetElement) {
                window.scrollTo({
                    top: targetElement.offsetTop - 60,
                    behavior: 'smooth'
                });
            }
        });
    });



    document.addEventListener("DOMContentLoaded", () => {
    function animateCounter(element, target, suffix = '', duration = 1000) {
        let start = 0;
        const increment = target / (duration / 16);
        const update = () => {
            start += increment;
            if (start >= target) {
                start = target;
                clearInterval(interval);
            }
            element.textContent = Math.floor(start) + suffix;
        };
        const interval = setInterval(update, 16);
    }

    function triggerCounters() {
        const counters = document.querySelectorAll(".stat-number");
        counters.forEach(counter => {
            const target = parseInt(counter.getAttribute("data-target"), 10);
            const suffix = counter.getAttribute("data-suffix") || '';
            if (!isNaN(target)) {
                animateCounter(counter, target, suffix);
            }
        });
    }

    const statsSection = document.querySelector(".stats-section");

    if ('IntersectionObserver' in window) {
        const observer = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    triggerCounters();
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });

        if (statsSection) observer.observe(statsSection);
    } else {
        // Fallback in case IntersectionObserver is not supported
        triggerCounters();
    }
});
</script>

    <!-- Hero Section -->
    <section class="hero-banner">
        <img src="assets/images/kdc.jpeg" alt="Campus Recruitment">
        <div class="hero-content">
            <h1>Launch Your Career Today</h1>
            <p>Connecting talented students with top employers through Fisat's recruitment platform</p>
            <div class="d-flex justify-content-center gap-3">
                <a href="students/student_register.php" class="btn btn-hero">Student Registration</a>
                <a href="recruiters/recruiter_register.php" class="btn btn-hero">Recruiter Registration</a>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about-section" class="about-section">
        <div class="container">
            <div class="section-title">
                <h2>About Fisat Campus Recruitment Portal</h2>
                <p>Bridging the gap between academia and industry for successful career placements</p>
            </div>
            
            <div class="about-content">
                <p>The Campus Recruitment Portal of Fisat is a comprehensive platform built to simplify and enhance the placement process for students and recruiters alike. In tune with the institution's commitment to academic excellence and holistic development, this portal serves as a digital gateway to countless career opportunities for our talented students.</p>
                
                <p>At Fisat, we strive to empower students to recognize and unlock their true potential. Our goal is to develop not only academically proficient graduates but also creative thinkers, problem-solvers, and future leaders. Through a strong foundation in education and a focus on adaptability in a fast-evolving world, we prepare our students to meet the demands of modern industries.</p>
                
                <p>This portal reflects that same philosophy—offering students a user-friendly interface to manage their profiles, apply for jobs, and track application statuses, while recruiters can easily post vacancies, review applications, and select the right candidates. The portal ensures transparency, accessibility, and efficiency throughout the recruitment cycle.</p>
                
                <p>Whether you're a student looking to start your career journey or a recruiter seeking fresh talent, our Campus Recruitment Portal is your trusted partner in building successful futures.</p>
            </div>
            
            <div class="row">
                <div class="col-md-4">
                    <div class="feature-box">
                        <div class="feature-icon">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <h4>For Students</h4>
                        <p>Find your dream job with top companies looking for fresh talent. Showcase your skills and connect with recruiters directly.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-box">
                        <div class="feature-icon">
                            <i class="fas fa-building"></i>
                        </div>
                        <h4>For Recruiters</h4>
                        <p>Discover the best young talent from our campus. Streamline your hiring process with our efficient platform.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-box">
                        <div class="feature-icon">
                            <i class="fas fa-handshake"></i>
                        </div>
                        <h4>Our Mission</h4>
                        <p>To create meaningful connections between academia and industry, fostering career growth and talent development.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!--Stats Section-->
    <section class="stats-section py-5 bg-light">
    <div class="container text-center">
        <div class="row">
            <div class="col-md-3 col-6 mb-4">
                <div class="stat-item">
                    <div class="stat-number" data-target="500" data-suffix="+">0</div>
                    <div class="stat-label">Students Placed</div>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-4">
                <div class="stat-item">
                    <div class="stat-number" data-target="100" data-suffix="+">0</div>
                    <div class="stat-label">Recruiting Partners</div>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-4">
                <div class="stat-item">
                    <div class="stat-number" data-target="95" data-suffix="%">0</div>
                    <div class="stat-label">Placement Rate</div>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-4">
                <div class="stat-item">
                    <div class="stat-number" data-target="24" data-suffix="/7">0</div>
                    <div class="stat-label">Portal Access</div>
                </div>
            </div>
        </div>
    </div>
</section>


    <!-- Footer Section -->
    <footer id="footer-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6 mb-4 mb-md-0">
                    <div class="footer-links">
                        <h5>Quick Links</h5>
                        <ul>
                            <li><a href="index.php"><i class="fas fa-chevron-right"></i> Home</a></li>
                            <li><a href="students/student_register.php"><i class="fas fa-chevron-right"></i> Student Registration</a></li>
                            <li><a href="recruiters/recruiter_register.php"><i class="fas fa-chevron-right"></i> Recruiter Registration</a></li>
                            <li><a href="admin/admin_login.php"><i class="fas fa-chevron-right"></i> Admin Login</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4 mb-md-0">
                    <div class="footer-links">
                        <h5>Contact Us</h5>
                        <ul>
                            <li><i class="fas fa-map-marker-alt"></i>12/1, Chikka Bellandur,Carmelaram Post Varthur Hobli, Off Sarjapur Rd, Bengaluru, Karnataka 560035</li>
                            <li><i class="fas fa-phone"></i>+91 87921 97272 / +91 70220 49950</li>
                            <li><i class="fas fa-envelope"></i>info@krupanidhi.edu.in</li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-4 col-md-12">
                    <div class="footer-links">
                        <h5>Follow Us</h5>
                        <div class="social-icons">
                           <i class="fab fa-facebook-f"></i></a>
                           <i class="fab fa-twitter"></i></a>
                           <i class="fab fa-linkedin-in"></i></a>
                           <i class="fab fa-instagram"></i></a>
                           <i class="fab fa-youtube"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 text-center copyright">
                    <p class="mb-0">&copy; <?php echo date("Y"); ?> Fisat College Campus Recruitment Portal. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>