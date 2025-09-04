<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parent Dashboard - School Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --primary: #4e73df;
            --secondary: #858796;
            --success: #1cc88a;
            --info: #36b9cc;
            --warning: #f6c23e;
            --danger: #e74a3b;
            --light: #f8f9fc;
            --dark: #5a5c69;
            --parent-primary: #4e73df;
            --parent-secondary: #6f42c1;
        }
        
        body {
            background-color: #f8f9fc;
            font-family: 'Nunito', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
        
        .parent-navbar {
            background: linear-gradient(90deg, var(--parent-primary) 0%, var(--parent-secondary) 100%);
        }
        
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, var(--parent-primary) 0%, var(--parent-secondary) 100%);
            color: white;
            position: fixed;
            width: 250px;
            z-index: 100;
        }
        
        .sidebar-brand {
            height: 70px;
            padding: 1.5rem 1rem;
            font-size: 1.2rem;
            font-weight: 800;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 0.05rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .sidebar-item {
            padding: 1rem;
            display: block;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.15s ease;
        }
        
        .sidebar-item:hover {
            color: white;
            background: rgba(255, 255, 255, 0.1);
        }
        
        .sidebar-item.active {
            color: white;
            background: rgba(255, 255, 255, 0.2);
            font-weight: bold;
        }
        
        .sidebar-item i {
            margin-right: 0.5rem;
            width: 20px;
            text-align: center;
        }
        
        .main-content {
            margin-left: 250px;
            padding: 20px;
        }
        
        .topbar {
            height: 70px;
            background: white;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px;
        }
        
        .card {
            border: none;
            border-radius: 0.35rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
            margin-bottom: 20px;
        }
        
        .card-header {
            background: white;
            border-bottom: 1px solid #e3e6f0;
            font-weight: bold;
            padding: 1rem 1.35rem;
        }
        
        .stat-card {
            border-left: 4px solid;
            transition: transform 0.2s;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }
        
        .stat-card.primary { border-left-color: var(--primary); }
        .stat-card.success { border-left-color: var(--success); }
        .stat-card.info { border-left-color: var(--info); }
        .stat-card.warning { border-left-color: var(--warning); }
        
        .stat-card .card-body {
            padding: 1.25rem;
        }
        
        .stat-card .text-xs {
            font-size: 0.9rem;
        }
        
        .stat-card .h5 {
            font-size: 1.5rem;
            font-weight: bold;
            margin: 0;
        }
        
        .child-card {
            transition: all 0.3s ease;
            border: 1px solid #e3e6f0;
        }
        
        .child-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 2rem 0 rgba(58, 59, 69, 0.2);
        }
        
        .child-card .card-img-top {
            height: 180px;
            object-fit: cover;
        }
        
        .child-card .progress {
            height: 10px;
        }
        
        .badge-success {
            background-color: var(--success);
        }
        
        .badge-warning {
            background-color: var(--warning);
        }
        
        .badge-danger {
            background-color: var(--danger);
        }
        
        .user-profile {
            display: flex;
            align-items: center;
        }
        
        .user-profile img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }
        
        .notification-dropdown .dropdown-menu {
            width: 320px;
            padding: 0;
        }
        
        .notification-item {
            padding: 1rem;
            border-bottom: 1px solid #e3e6f0;
        }
        
        .notification-item:last-child {
            border-bottom: none;
        }
        
        .notification-item.unread {
            background-color: #f8f9fc;
        }
        
        .chart-container {
            position: relative;
            height: 250px;
        }
        
        .activity-item {
            border-left: 3px solid var(--parent-primary);
            padding-left: 15px;
            margin-bottom: 20px;
            position: relative;
        }
        
        .activity-item::before {
            content: '';
            position: absolute;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: var(--parent-primary);
            left: -7.5px;
            top: 5px;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }
            
            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-brand">
            <i class="fas fa-user-friends"></i> Parent Portal
        </div>
        
        <a href="#" class="sidebar-item active">
            <i class="fas fa-fw fa-tachometer-alt"></i> Dashboard
        </a>
        <a href="#" class="sidebar-item">
            <i class="fas fa-fw fa-child"></i> My Children
        </a>
        <a href="#" class="sidebar-item">
            <i class="fas fa-fw fa-calendar-check"></i> Attendance
        </a>
        <a href="#" class="sidebar-item">
            <i class="fas fa-fw fa-chart-line"></i> Grades & Progress
        </a>
        <a href="#" class="sidebar-item">
            <i class="fas fa-fw fa-file-invoice-dollar"></i> Payments
        </a>
        <a href="#" class="sidebar-item">
            <i class="fas fa-fw fa-calendar-alt"></i> School Calendar
        </a>
        <a href="#" class="sidebar-item">
            <i class="fas fa-fw fa-bell"></i> Notifications
        </a>
        <a href="#" class="sidebar-item">
            <i class="fas fa-fw fa-cog"></i> Settings
        </a>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Topbar -->
        <div class="topbar">
            <button id="sidebarToggle" class="btn btn-link d-md-none">
                <i class="fas fa-bars"></i>
            </button>
            
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown no-arrow mx-1">
                    <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-bell fa-fw"></i>
                        <span class="badge bg-danger badge-counter">5</span>
                    </a>
                    <div class="dropdown-list dropdown-menu dropdown-menu-end shadow animated--grow-in"
                        aria-labelledby="alertsDropdown">
                        <h6 class="dropdown-header">Alerts Center</h6>
                        <a class="dropdown-item d-flex align-items-center" href="#">
                            <div class="mr-3">
                                <div class="icon-circle bg-warning">
                                    <i class="fas fa-exclamation-triangle text-white"></i>
                                </div>
                            </div>
                            <div>
                                <div class="small text-gray-500">Today</div>
                                <span class="font-weight-bold">John was marked absent in Math class</span>
                            </div>
                        </a>
                        <a class="dropdown-item d-flex align-items-center" href="#">
                            <div class="mr-3">
                                <div class="icon-circle bg-success">
                                    <i class="fas fa-file-alt text-white"></i>
                                </div>
                            </div>
                            <div>
                                <div class="small text-gray-500">Yesterday</div>
                                <span class="font-weight-bold">New report card available for Sarah</span>
                            </div>
                        </a>
                        <a class="dropdown-item text-center small text-gray-500" href="#">Show All Alerts</a>
                    </div>
                </li>

                <div class="topbar-divider d-none d-sm-block"></div>

                <!-- User Information -->
                <li class="nav-item dropdown no-arrow">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="d-none d-lg-inline text-gray-600 small">Robert Smith</span>
                        <img class="img-profile rounded-circle" src="https://via.placeholder.com/40">
                    </a>
                    <div class="dropdown-menu dropdown-menu-end shadow animated--grow-in"
                        aria-labelledby="userDropdown">
                        <a class="dropdown-item" href="#">
                            <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                            Profile
                        </a>
                        <a class="dropdown-item" href="#">
                            <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                            Settings
                        </a>
                        <a class="dropdown-item" href="#">
                            <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                            Activity Log
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal">
                            <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                            Logout
                        </a>
                    </div>
                </li>
            </ul>
        </div>

        <!-- Page Content -->
        <div class="container-fluid">
            <!-- Page Heading -->
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Parent Dashboard</h1>
                <span class="text-muted">Welcome back, Robert!</span>
            </div>

            <!-- My Children Section -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card shadow">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">My Children</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <!-- Child 1 -->
                                <div class="col-xl-4 col-md-6 mb-4">
                                    <div class="card child-card h-100">
                                        <img src="https://via.placeholder.com/300x180?text=John+Smith" class="card-img-top" alt="John Smith">
                                        <div class="card-body">
                                            <h5 class="card-title">John Smith</h5>
                                            <p class="card-text">
                                                <i class="fas fa-graduation-cap text-primary me-2"></i> Grade 10-A
                                                <br>
                                                <i class="fas fa-book text-info me-2"></i> Mathematics, Science, English
                                            </p>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="badge bg-success">Present Today</span>
                                                <a href="#" class="btn btn-sm btn-outline-primary">View Details</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Child 2 -->
                                <div class="col-xl-4 col-md-6 mb-4">
                                    <div class="card child-card h-100">
                                        <img src="https://via.placeholder.com/300x180?text=Sarah+Smith" class="card-img-top" alt="Sarah Smith">
                                        <div class="card-body">
                                            <h5 class="card-title">Sarah Smith</h5>
                                            <p class="card-text">
                                                <i class="fas fa-graduation-cap text-primary me-2"></i> Grade 8-B
                                                <br>
                                                <i class="fas fa-book text-info me-2"></i> Arts, History, Geography
                                            </p>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="badge bg-warning text-dark">Late Today</span>
                                                <a href="#" class="btn btn-sm btn-outline-primary">View Details</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Add Child -->
                                <div class="col-xl-4 col-md-6 mb-4">
                                    <div class="card h-100">
                                        <div class="card-body d-flex flex-column justify-content-center align-items-center text-center">
                                            <div class="mb-3">
                                                <i class="fas fa-plus-circle fa-3x text-gray-300"></i>
                                            </div>
                                            <h5 class="card-title text-gray-500">Add Another Child</h5>
                                            <p class="card-text text-gray-500">Register a new child to your account</p>
                                            <a href="#" class="btn btn-primary">Add Child</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Row -->
            <div class="row">
                <!-- Attendance Summary -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card stat-card primary h-100">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Overall Attendance</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">94%</div>
                                    <div class="mt-2 mb-0 text-muted text-xs">
                                        <span class="text-success mr-2"><i class="fas fa-arrow-up"></i> 3%</span>
                                        <span>from last month</span>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-calendar-check fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Average Grades -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card stat-card success h-100">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        Average Grades</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">B+</div>
                                    <div class="mt-2 mb-0 text-muted text-xs">
                                        <span class="text-success mr-2"><i class="fas fa-arrow-up"></i> 0.5</span>
                                        <span>GPA improvement</span>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pending Payments -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card stat-card info h-100">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                        Pending Payments</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">2</div>
                                    <div class="mt-2 mb-0 text-muted text-xs">
                                        <span class="text-danger mr-2"><i class="fas fa-exclamation-circle"></i></span>
                                        <span>Due in 5 days</span>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Upcoming Events -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card stat-card warning h-100">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                        Upcoming Events</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">3</div>
                                    <div class="mt-2 mb-0 text-muted text-xs">
                                        <span class="text-warning mr-2"><i class="fas fa-calendar"></i></span>
                                        <span>Next: Parent-Teacher Meeting</span>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-calendar-day fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Row -->
            <div class="row">
                <!-- Grades Overview -->
                <div class="col-xl-8 col-lg-7">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Academic Performance Overview</h6>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="gradesChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Attendance Distribution -->
                <div class="col-xl-4 col-lg-5">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Attendance Distribution</h6>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="attendanceChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Row -->
            <div class="row">
                <!-- Recent Activities -->
                <div class="col-lg-6 mb-4">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Recent Activities</h6>
                        </div>
                        <div class="card-body">
                            <div class="activity-feed">
                                <div class="activity-item">
                                    <div class="small text-gray-500">Today, 2:30 PM</div>
                                    <div class="activity-content">
                                        <i class="fas fa-check-circle text-success me-2"></i> John submitted his Science project
                                    </div>
                                </div>
                                <div class="activity-item">
                                    <div class="small text-gray-500">Today, 10:15 AM</div>
                                    <div class="activity-content">
                                        <i class="fas fa-exclamation-triangle text-warning me-2"></i> Sarah was marked late for school
                                    </div>
                                </div>
                                <div class="activity-item">
                                    <div class="small text-gray-500">Yesterday, 4:45 PM</div>
                                    <div class="activity-content">
                                        <i class="fas fa-chart-line text-info me-2"></i> New grades posted for Mathematics
                                    </div>
                                </div>
                                <div class="activity-item">
                                    <div class="small text-gray-500">October 26, 2023</div>
                                    <div class="activity-content">
                                        <i class="fas fa-calendar-plus text-primary me-2"></i> Parent-Teacher meeting scheduled for November 5
                                    </div>
                                </div>
                                <div class="activity-item">
                                    <div class="small text-gray-500">October 25, 2023</div>
                                    <div class="activity-content">
                                        <i class="fas fa-money-bill-wave text-success me-2"></i> Tuition payment received
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Upcoming Events -->
                <div class="col-lg-6 mb-4">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Upcoming Events</h6>
                        </div>
                        <div class="card-body">
                            <div class="upcoming-events">
                                <div class="event-item mb-3 p-3 bg-light rounded">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0">Parent-Teacher Meeting</h6>
                                        <span class="badge bg-primary">Nov 5</span>
                                    </div>
                                    <div class="small text-gray-500">
                                        <i class="fas fa-clock me-1"></i> 2:00 PM - 4:00 PM
                                        <i class="fas fa-map-marker-alt ms-3 me-1"></i> School Conference Room
                                    </div>
                                </div>
                                <div class="event-item mb-3 p-3 bg-light rounded">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0">Science Fair</h6>
                                        <span class="badge bg-success">Nov 12</span>
                                    </div>
                                    <div class="small text-gray-500">
                                        <i class="fas fa-clock me-1"></i> 9:00 AM - 3:00 PM
                                        <i class="fas fa-map-marker-alt ms-3 me-1"></i> School Gymnasium
                                    </div>
                                </div>
                                <div class="event-item mb-3 p-3 bg-light rounded">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0">Sports Day</h6>
                                        <span class="badge bg-info">Nov 18</span>
                                    </div>
                                    <div class="small text-gray-500">
                                        <i class="fas fa-clock me-1"></i> All Day
                                        <i class="fas fa-map-marker-alt ms-3 me-1"></i> School Grounds
                                    </div>
                                </div>
                                <div class="event-item mb-3 p-3 bg-light rounded">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0">End of Term Exams</h6>
                                        <span class="badge bg-warning text-dark">Dec 5-10</span>
                                    </div>
                                    <div class="small text-gray-500">
                                        <i class="fas fa-clock me-1"></i> All Day
                                        <i class="fas fa-map-marker-alt ms-3 me-1"></i> Various Classrooms
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="login.html">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Grades Chart
            const gradesCtx = document.getElementById('gradesChart').getContext('2d');
            const gradesChart = new Chart(gradesCtx, {
                type: 'bar',
                data: {
                    labels: ['Mathematics', 'Science', 'English', 'History', 'Arts', 'Geography'],
                    datasets: [{
                        label: 'John Smith',
                        data: [85, 92, 78, 88, 75, 82],
                        backgroundColor: 'rgba(78, 115, 223, 0.7)',
                        borderColor: 'rgba(78, 115, 223, 1)',
                        borderWidth: 1
                    }, {
                        label: 'Sarah Smith',
                        data: [79, 85, 90, 82, 88, 80],
                        backgroundColor: 'rgba(28, 200, 138, 0.7)',
                        borderColor: 'rgba(28, 200, 138, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: false,
                            min: 50,
                            max: 100,
                            ticks: {
                                callback: function(value) {
                                    return value + '%';
                                }
                            }
                        }
                    }
                }
            });

            // Attendance Chart
            const attendanceCtx = document.getElementById('attendanceChart').getContext('2d');
            const attendanceChart = new Chart(attendanceCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Present', 'Late', 'Absent'],
                    datasets: [{
                        data: [94, 4, 2],
                        backgroundColor: ['#1cc88a', '#f6c23e', '#e74a3b'],
                        hoverBackgroundColor: ['#17a673', '#dda20a', '#be2617'],
                        hoverBorderColor: 'rgba(234, 236, 244, 1)',
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    cutout: '70%',
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });

            // Toggle sidebar on mobile
            document.getElementById('sidebarToggle').addEventListener('click', function() {
                document.querySelector('.sidebar').classList.toggle('toggled');
            });
        });
    </script>
</body>
</html>