<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'School Management System') }}</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Custom CSS -->
    <style>
        :root {
            /* Professional Color Scheme */
            --primary: #2563eb;
            --primary-light: #3b82f6;
            --primary-dark: #1d4ed8;
            --secondary: #64748b;
            --accent: #f59e0b;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --info: #0ea5e9;
            --background: #f8fafc;
            --surface: #ffffff;
            --text-primary: #1e293b;
            --text-secondary: #64748b;
            --border: #e2e8f0;
            --shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --border-radius: 8px;
            --border-radius-sm: 6px;
            --transition: all 0.2s ease-in-out;
            --sidebar-width: 280px;
            --sidebar-collapsed-width: 70px;
        }

        body.dark-mode {
            --primary: #3b82f6;
            --primary-light: #60a5fa;
            --primary-dark: #2563eb;
            --secondary: #94a3b8;
            --background: #0f172a;
            --surface: #1e293b;
            --text-primary: #f1f5f9;
            --text-secondary: #cbd5e1;
            --border: #334155;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: var(--background);
            color: var(--text-primary);
            line-height: 1.6;
            transition: var(--transition);
        }

        /* Layout Components */
        .dashboard-container {
            display: grid;
            grid-template-columns: var(--sidebar-width) 1fr;
            min-height: 100vh;
            transition: var(--transition);
        }

        .sidebar-collapsed .dashboard-container {
            grid-template-columns: var(--sidebar-collapsed-width) 1fr;
        }

        /* Sidebar - Redesigned */
        .sidebar {
            background: var(--surface);
            border-right: 1px solid var(--border);
            padding: 1rem 0;
            position: fixed;
            width: var(--sidebar-width);
            height: 100vh;
            overflow-y: auto;
            z-index: 1000;
            transition: var(--transition);
            display: flex;
            flex-direction: column;
        }

        .sidebar-collapsed .sidebar {
            width: var(--sidebar-collapsed-width);
        }

        .sidebar-header {
            padding: 0.5rem 1rem 1rem;
            border-bottom: 1px solid var(--border);
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .sidebar-brand {
            font-weight: 700;
            font-size: 1.25rem;
            color: var(--primary);
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem;
            white-space: nowrap;
            overflow: hidden;
        }

        .sidebar-collapsed .sidebar-brand span {
            display: none;
        }

        .sidebar-toggle {
            background: none;
            border: none;
            color: var(--text-secondary);
            cursor: pointer;
            font-size: 1rem;
            padding: 0.25rem;
            border-radius: var(--border-radius-sm);
            transition: var(--transition);
        }

        .sidebar-toggle:hover {
            background-color: rgba(37, 99, 235, 0.1);
            color: var(--primary);
        }

        .sidebar-collapsed .sidebar-toggle {
            transform: rotate(180deg);
        }

        .nav-item {
            margin-bottom: 0.25rem;
            position: relative;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            border-radius: var(--border-radius-sm);
            color: var(--text-secondary);
            font-weight: 500;
            transition: var(--transition);
            font-size: 0.9rem;
            text-decoration: none;
            white-space: nowrap;
            margin: 0 0.5rem;
        }

        .nav-link:hover, .nav-link.active {
            background-color: rgba(37, 99, 235, 0.08);
            color: var(--primary);
            text-decoration: none;
        }

        .sidebar-collapsed .nav-link span {
            display: none;
        }

        .nav-section {
            margin: 1rem 0 0.5rem;
            padding: 0 1rem;
        }

        .nav-section-title {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-secondary);
            font-weight: 600;
            margin-bottom: 0.5rem;
            white-space: nowrap;
            overflow: hidden;
        }

        .sidebar-collapsed .nav-section-title {
            display: none;
        }

        /* Dropdown Menu Styles - Redesigned */
        .dropdown-nav {
            position: relative;
        }

        .dropdown-toggle {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            cursor: pointer;
        }

        .dropdown-icon {
            transition: transform 0.2s ease-in-out;
            font-size: 0.8rem;
        }

        .dropdown-toggle.show .dropdown-icon {
            transform: rotate(180deg);
        }

        .dropdown-menu-nav {
            display: none;
            background: rgba(37, 99, 235, 0.05);
            border-radius: var(--border-radius-sm);
            margin: 0.25rem 0.5rem 0.25rem 2.5rem;
            padding: 0.25rem 0;
            border-left: 2px solid var(--primary);
            transition: var(--transition);
        }

        .dropdown-menu-nav.show {
            display: block;
        }

        .dropdown-item-nav {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem 1rem 0.5rem 1rem;
            color: var(--text-secondary);
            font-weight: 400;
            transition: var(--transition);
            font-size: 0.85rem;
            text-decoration: none;
            border-radius: var(--border-radius-sm);
            margin: 0 0.25rem;
        }

        .dropdown-item-nav:hover {
            background-color: rgba(37, 99, 235, 0.1);
            color: var(--primary);
            text-decoration: none;
        }

        .dropdown-item-nav i {
            width: 16px;
            text-align: center;
            font-size: 0.8rem;
        }

        .sidebar-collapsed .dropdown-menu-nav {
            position: absolute;
            left: 100%;
            top: 0;
            width: 200px;
            background: var(--surface);
            border: 1px solid var(--border);
            box-shadow: var(--shadow-lg);
            z-index: 1000;
            margin: 0;
            padding: 0.5rem;
        }

        .sidebar-collapsed .dropdown-item-nav {
            padding: 0.75rem 1rem;
        }

        /* Main Content */
        .main-content {
            grid-column: 2;
            padding: 1.5rem;
            background-color: var(--background);
            transition: var(--transition);
        }

        .sidebar-collapsed .main-content {
            grid-column: 2;
            margin-left: calc(var(--sidebar-collapsed-width) - var(--sidebar-width));
        }

        /* Header - Professional Style */
        .header {
            background: var(--surface);
            padding: 1rem 1.5rem;
            box-shadow: var(--shadow);
            position: sticky;
            top: 0;
            z-index: 100;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            border-radius: var(--border-radius);
            border: 1px solid var(--border);
        }

        .search-box {
            position: relative;
            max-width: 300px;
            width: 100%;
        }

        .search-input {
            width: 100%;
            padding: 0.625rem 1rem 0.625rem 2.5rem;
            border: 1px solid var(--border);
            border-radius: var(--border-radius-sm);
            background-color: var(--surface);
            transition: var(--transition);
            color: var(--text-primary);
            font-size: 0.9rem;
        }

        .search-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .search-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-secondary);
            font-size: 0.9rem;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        /* Theme Toggle */
        .theme-toggle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--surface);
            border: 1px solid var(--border);
            cursor: pointer;
            transition: var(--transition);
            color: var(--text-primary);
        }

        .theme-toggle:hover {
            background: rgba(37, 99, 235, 0.1);
            color: var(--primary);
        }

        /* Stats Cards - Professional Style */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .stat-card {
            background: var(--surface);
            border-radius: var(--border-radius);
            padding: 1.25rem;
            box-shadow: var(--shadow);
            transition: var(--transition);
            border: 1px solid var(--border);
            position: relative;
            overflow: hidden;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .stat-icon {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
            font-size: 1.1rem;
            background: linear-gradient(135deg, var(--primary-light), var(--primary));
            color: white;
        }

        .stat-value {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
            color: var(--text-primary);
            font-feature-settings: 'tnum';
        }

        .stat-label {
            color: var(--text-secondary);
            font-weight: 500;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }

        .stat-trend {
            display: flex;
            align-items: center;
            gap: 0.25rem;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .trend-up { color: var(--success); }
        .trend-down { color: var(--danger); }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .dashboard-container {
                grid-template-columns: 1fr;
            }

            .sidebar {
                transform: translateX(-100%);
                z-index: 1050;
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .main-content {
                grid-column: 1;
                padding: 1rem;
            }

            .sidebar-collapsed .sidebar {
                width: var(--sidebar-width);
            }
            
            .sidebar-collapsed .dashboard-container {
                grid-template-columns: 1fr;
            }
            
            .sidebar-collapsed .main-content {
                margin-left: 0;
            }
        }

        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                gap: 1rem;
                align-items: stretch;
                padding: 1rem;
            }

            .search-box {
                max-width: 100%;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }
        }

        /* User menu */
        .user-menu {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: var(--border-radius-sm);
            transition: var(--transition);
        }

        .user-menu:hover {
            background-color: rgba(37, 99, 235, 0.1);
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-light), var(--primary));
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .user-name {
            font-weight: 500;
            color: var(--text-primary);
            font-size: 0.9rem;
        }

        .user-role {
            font-size: 0.8rem;
            color: var(--text-secondary);
        }

        /* Dropdown menu */
        .dropdown-menu {
            border: 1px solid var(--border);
            box-shadow: var(--shadow-lg);
            background-color: var(--surface);
            border-radius: var(--border-radius);
            padding: 0.5rem;
        }

        .dropdown-item {
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 0.75rem;
            transition: var(--transition);
            border-radius: var(--border-radius-sm);
            font-size: 0.9rem;
        }

        .dropdown-item:hover {
            background-color: rgba(37, 99, 235, 0.1);
            color: var(--primary);
        }

        /* Notification badge */
        .notification-badge {
            position: relative;
        }

        .badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: linear-gradient(135deg, var(--danger), #dc2626);
            color: white;
            border-radius: 50%;
            width: 16px;
            height: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.65rem;
            font-weight: 600;
        }

        /* Alerts */
        .alert {
            border: none;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            border-left: 4px solid transparent;
            padding: 1rem 1.25rem;
        }

        .alert-success {
            background-color: rgba(16, 185, 129, 0.1);
            color: #065f46;
            border-left-color: var(--success);
        }

        .alert-danger {
            background-color: rgba(239, 68, 68, 0.1);
            color: #991b1b;
            border-left-color: var(--danger);
        }

        body.dark-mode .alert-success {
            background-color: rgba(16, 185, 129, 0.15);
            color: #6ee7b7;
        }

        body.dark-mode .alert-danger {
            background-color: rgba(239, 68, 68, 0.15);
            color: #fca5a5;
        }

        /* Mobile menu button */
        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            font-size: 1.25rem;
            color: var(--text-primary);
            cursor: pointer;
            padding: 0.5rem;
            border-radius: var(--border-radius-sm);
        }

        .mobile-menu-btn:hover {
            background-color: rgba(37, 99, 235, 0.1);
        }

        @media (max-width: 1024px) {
            .mobile-menu-btn {
                display: block;
            }
        }

        /* Logout button in sidebar */
        .sidebar-logout {
            margin-top: auto;
            padding: 1rem 0.5rem;
            border-top: 1px solid var(--border);
        }

        .sidebar-logout .btn {
            width: 100%;
            justify-content: center;
        }
        
        .sidebar-collapsed .sidebar-logout .btn span {
            display: none;
        }
        
        .tooltip-item {
            position: relative;
        }
        
        .sidebar-collapsed .nav-link::after {
            content: attr(data-tooltip);
            position: absolute;
            left: 100%;
            top: 50%;
            transform: translateY(-50%);
            background: var(--surface);
            color: var(--text-primary);
            padding: 0.5rem 0.75rem;
            border-radius: var(--border-radius-sm);
            font-size: 0.8rem;
            white-space: nowrap;
            opacity: 0;
            visibility: hidden;
            transition: var(--transition);
            box-shadow: var(--shadow-lg);
            border: 1px solid var(--border);
            z-index: 1000;
        }
        
        .sidebar-collapsed .nav-link:hover::after {
            opacity: 1;
            visibility: visible;
            left: calc(100% + 10px);
        }
    </style>
</head>
<body>
    <div class="dashboard-container" id="dashboardContainer">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <div class="sidebar-brand">
                    <i class="fas fa-graduation-cap"></i>
                    <span>ShuleNet</span>
                </div>
                <button class="sidebar-toggle" id="sidebarToggle">
                    <i class="fas fa-chevron-left"></i>
                </button>
            </div>

            <nav class="sidebar-nav">
                <ul class="nav flex-column">
                    @if(Auth::check() && Auth::user()->type === 'teacher')
                        <div class="nav-section">
                            <div class="nav-section-title">Main</div>
                        </div>
                        <li class="nav-item"><a class="nav-link active" href="{{ route('teacher.dashboard') }}" data-tooltip="Dashboard"><i class="fas fa-tachometer-alt"></i><span>Dashboard</span></a></li>
                        
                        <div class="nav-section">
                            <div class="nav-section-title">Management</div>
                        </div>
                        <li class="nav-item"><a class="nav-link" href="#" data-tooltip="Students"><i class="fas fa-users"></i><span>Students</span></a></li>
                        <li class="nav-item"><a class="nav-link" href="#" data-tooltip="Attendance"><i class="fas fa-calendar-check"></i><span>Attendance</span></a></li>
                        <li class="nav-item"><a class="nav-link" href="#" data-tooltip="Grades"><i class="fas fa-chart-line"></i><span>Grades</span></a></li>
                        
                    @elseif(Auth::check() && Auth::user()->type === 'parent')
                        <div class="nav-section">
                            <div class="nav-section-title">Main</div>
                        </div>
                        <li class="nav-item"><a class="nav-link active" href="{{ route('frontend.dashboard') }}" data-tooltip="Dashboard"><i class="fas fa-tachometer-alt"></i><span>Dashboard</span></a></li>
                        
                        <div class="nav-section">
                            <div class="nav-section-title">Family</div>
                        </div>
                        <li class="nav-item"><a class="nav-link" href="#" data-tooltip="My Children"><i class="fas fa-child"></i><span>My Children</span></a></li>
                        <li class="nav-item"><a class="nav-link" href="#" data-tooltip="Invoices"><i class="fas fa-file-invoice"></i><span>Invoices</span></a></li>
                        
                    @elseif(Auth::check() && Auth::user()->type === 'admin')
                        <div class="nav-section">
                            <div class="nav-section-title">Main</div>
                        </div>
                        <li class="nav-item"><a class="nav-link active" href="{{ route('admin.dashboard') }}" data-tooltip="Dashboard"><i class="fas fa-tachometer-alt"></i><span>Dashboard</span></a></li>
                        
                        <div class="nav-section">
                            <div class="nav-section-title">User Management</div>
                        </div>
                        <li class="nav-item"><a class="nav-link" href="{{ route('admin.userManagement') }}" data-tooltip="Users Management"><i class="fas fa-users"></i><span>Users Management</span></a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('admin.teacherManagement') }}" data-tooltip="Roles & Permissions"><i class="fas fa-chalkboard-teacher"></i><span>Roles & Permissions</span></a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('admin.create_student_parent') }}" data-tooltip="Student/Parent Control"><i class="fas fa-plus-circle"></i><span>Student/Parent Control</span></a></li>
                        
                        <div class="nav-section">
                            <div class="nav-section-title">Financial</div>
                        </div>
                        <li class="nav-item"><a class="nav-link" href="{{ route('invoices.index') }}" data-tooltip="Payments & Invoices"><i class="fas fa-hand-holding-usd"></i><span>Payments & Invoices</span></a></li>
                        
                        <div class="nav-section">
                            <div class="nav-section-title">Academic</div>
                        </div>
                        <li class="nav-item"><a class="nav-link" href="#" data-tooltip="Attendance"><i class="fas fa-calendar-check"></i><span>Attendance</span></a></li>
                        
                        <!-- Classes/Subject Management Dropdown -->
                        <li class="nav-item dropdown-nav">
                            <div class="nav-link dropdown-toggle" onclick="toggleDropdown(this)" data-tooltip="Classes/Subject Management">
                                <div style="display: flex; align-items: center; gap: 0.75rem;">
                                    <i class="fas fa-chalkboard"></i>
                                    <span>Classes/Subject Control</span>
                                </div>
                                <i class="fas fa-chevron-down dropdown-icon"></i>
                            </div>
                            <div class="dropdown-menu-nav">
                                <a class="dropdown-item-nav" href="{{ route('classes.index') }}">
                                    <i class="fas fa-list"></i>
                                    <span>View All Classes</span>
                                </a>
                                <a class="dropdown-item-nav" href="{{ route('classes.create') }}">
                                    <i class="fas fa-plus"></i>
                                    <span>Create New Class</span>
                                </a>
                                <a class="dropdown-item-nav" href="{{ route('classes.edit', 1) }}"> 
                                    <i class="fas fa-edit"></i>
                                    <span>Edit Class</span>
                                </a>
                            </div>
                        </li>
                        
                        <li class="nav-item dropdown-nav">
                            <div class="nav-link dropdown-toggle" onclick="toggleDropdown(this)" data-tooltip="Subjects Management">
                                <div style="display: flex; align-items: center; gap: 0.75rem;">
                                    <i class="fas fa-book"></i>
                                    <span>Subjects Management</span>
                                </div>
                                <i class="fas fa-chevron-down dropdown-icon"></i>
                            </div>
                            <div class="dropdown-menu-nav">
                                <a class="dropdown-item-nav" href="{{ route('subjects.index') }}">
                                    <i class="fas fa-list"></i>
                                    <span>View All Subjects</span>
                                </a>
                                <a class="dropdown-item-nav" href="{{ route('subjects.create') }}">
                                    <i class="fas fa-plus"></i>
                                    <span>Create New Subject</span>
                                </a>
                                <a class="dropdown-item-nav" href="{{ route('subjects.edit', 1) }}"> 
                                    <i class="fas fa-edit"></i>
                                    <span>Edit Subject</span>
                                </a>
                                <a class="dropdown-item-nav" href="{{ route('assignments.index') }}">
                                    <i class="fas fa-link"></i>
                                    <span>Student-Class Assignments</span>
                                </a>
                                <a class="dropdown-item-nav" href="{{ route('assignments.create') }}">
                                    <i class="fas fa-user-plus"></i>
                                    <span>Assign Student to Class</span>
                                </a>
                                <a class="dropdown-item-nav" href="{{ route('assignments.bulk.create') }}">
                                    <i class="fas fa-users"></i>
                                    <span>Bulk Assign Students</span>
                                </a>
                            </div>
                        </li>
                        
                        <div class="nav-section">
                            <div class="nav-section-title">Other</div>
                        </div>
                        <li class="nav-item"><a class="nav-link" href="#" data-tooltip="Result Management"><i class="fas fa-chart-line"></i><span>Result Management</span></a></li>
                        <li class="nav-item"><a class="nav-link" href="#" data-tooltip="Events Control"><i class="fas fa-calendar-check"></i><span>Events Control</span></a></li>
                        <li class="nav-item"><a class="nav-link" href="#" data-tooltip="Announcements"><i class="fas fa-bullhorn"></i><span>Announcements</span></a></li>
                    @endif
                </ul>
                
                <div class="sidebar-logout">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="fas fa-sign-out-alt me-1"></i> <span>Logout</span>
                        </button>
                    </form>
                </div>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Header -->
            <header class="header">
                <button class="mobile-menu-btn" id="mobileMenuBtn">
                    <i class="fas fa-bars"></i>
                </button>

                <div class="search-box">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" class="search-input" placeholder="Search...">
                </div>

                <div class="header-actions">
                    <button class="theme-toggle" id="themeToggle">
                        <i class="fas fa-moon"></i>
                    </button>

                    <div class="notification-badge">
                        <button class="btn btn-icon">
                            <i class="fas fa-bell"></i>
                        </button>
                        <span class="badge">3</span>
                    </div>

                    <div class="dropdown">
                        <div class="user-menu" data-bs-toggle="dropdown">
                            <div class="user-avatar">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <div class="user-info d-none d-md-block">
                                <div class="user-name text-truncate" style="max-width: 120px;">{{ Auth::user()->name }}</div>
                                <div class="user-role text-capitalize">{{ Auth::user()->type }}</div>
                            </div>
                            <i class="fas fa-chevron-down"></i>
                        </div>

                        <div class="dropdown-menu dropdown-menu-end">
                            <a class="dropdown-item" href="{{ route('profile') }}"><i class="fas fa-user"></i><span>Profile</span></a>
                            <a class="dropdown-item" href="#"><i class="fas fa-cog"></i><span>Settings</span></a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="{{ route('logout') }}"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt"></i><span>Logout</span>
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Alerts -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Main Content Area -->
            @yield('content')
        </main>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize theme
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.body.classList.toggle('dark-mode', savedTheme === 'dark');
            updateThemeIcon();

            // Initialize sidebar state
            const sidebarState = localStorage.getItem('sidebarState') || 'expanded';
            if (sidebarState === 'collapsed') {
                document.body.classList.add('sidebar-collapsed');
            }

            // Mobile menu toggle
            const mobileMenuBtn = document.getElementById('mobileMenuBtn');
            const sidebar = document.querySelector('.sidebar');

            mobileMenuBtn.addEventListener('click', function() {
                sidebar.classList.toggle('open');
            });

            // Theme toggle functionality
            document.getElementById('themeToggle').addEventListener('click', toggleTheme);

            // Sidebar toggle functionality
            document.getElementById('sidebarToggle').addEventListener('click', toggleSidebar);

            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', function(event) {
                if (window.innerWidth < 1024 && 
                    !sidebar.contains(event.target) && 
                    !mobileMenuBtn.contains(event.target) &&
                    sidebar.classList.contains('open')) {
                    sidebar.classList.remove('open');
                }
            });
            
            // Initialize dropdowns
            initDropdowns();
        });

        function toggleTheme() {
            const body = document.body;
            const isDark = body.classList.toggle('dark-mode');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
            updateThemeIcon();
        }

        function updateThemeIcon() {
            const icon = document.getElementById('themeToggle').querySelector('i');
            icon.className = document.body.classList.contains('dark-mode') ? 'fas fa-sun' : 'fas fa-moon';
        }

        function toggleSidebar() {
            const body = document.body;
            const isCollapsed = body.classList.toggle('sidebar-collapsed');
            localStorage.setItem('sidebarState', isCollapsed ? 'collapsed' : 'expanded');
            
            // Close all dropdowns when collapsing sidebar
            if (isCollapsed) {
                document.querySelectorAll('.dropdown-menu-nav.show').forEach(menu => {
                    menu.classList.remove('show');
                    menu.previousElementSibling.classList.remove('show');
                });
            }
        }

        // Improved dropdown toggle function
        function toggleDropdown(element) {
            // If sidebar is collapsed, don't toggle dropdown on click (handled by hover)
            if (document.body.classList.contains('sidebar-collapsed')) {
                return;
            }
            
            const dropdownMenu = element.nextElementSibling;
            const isCurrentlyOpen = dropdownMenu.classList.contains('show');
            
            // Close all other dropdowns
            document.querySelectorAll('.dropdown-menu-nav.show').forEach(menu => {
                if (menu !== dropdownMenu) {
                    menu.classList.remove('show');
                    menu.previousElementSibling.classList.remove('show');
                }
            });
            
            // Toggle current dropdown
            if (isCurrentlyOpen) {
                dropdownMenu.classList.remove('show');
                element.classList.remove('show');
            } else {
                dropdownMenu.classList.add('show');
                element.classList.add('show');
            }
        }

        // Initialize dropdown functionality
        function initDropdowns() {
            // Handle dropdowns in collapsed sidebar (show on hover)
            document.querySelectorAll('.dropdown-nav').forEach(dropdown => {
                dropdown.addEventListener('mouseenter', function() {
                    if (document.body.classList.contains('sidebar-collapsed')) {
                        const dropdownMenu = this.querySelector('.dropdown-menu-nav');
                        const dropdownToggle = this.querySelector('.dropdown-toggle');
                        
                        // Close all other dropdowns
                        document.querySelectorAll('.dropdown-menu-nav.show').forEach(menu => {
                            if (menu !== dropdownMenu) {
                                menu.classList.remove('show');
                                menu.previousElementSibling.classList.remove('show');
                            }
                        });
                        
                        // Show this dropdown
                        dropdownMenu.classList.add('show');
                        dropdownToggle.classList.add('show');
                    }
                });
                
                dropdown.addEventListener('mouseleave', function() {
                    if (document.body.classList.contains('sidebar-collapsed')) {
                        const dropdownMenu = this.querySelector('.dropdown-menu-nav');
                        const dropdownToggle = this.querySelector('.dropdown-toggle');
                        
                        dropdownMenu.classList.remove('show');
                        dropdownToggle.classList.remove('show');
                    }
                });
            });
            
            // Close dropdown when clicking outside
            document.addEventListener('click', function(event) {
                if (!event.target.closest('.dropdown-nav')) {
                    document.querySelectorAll('.dropdown-menu-nav.show').forEach(menu => {
                        // Only close if sidebar is not collapsed
                        if (!document.body.classList.contains('sidebar-collapsed')) {
                            menu.classList.remove('show');
                            menu.previousElementSibling.classList.remove('show');
                        }
                    });
                }
            });
            
            // Prevent dropdown from closing when clicking inside
            document.querySelectorAll('.dropdown-menu-nav').forEach(menu => {
                menu.addEventListener('click', function(e) {
                    e.stopPropagation();
                });
            });
        }
    </script>
</body>
</html>