<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'Dashboard Marketing'; ?></title>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- AdminLTE -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.3.0/dist/chart.umd.js"></script>
    
    <style>
        .info-box {
            min-height: 100px;
            box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
            border-radius: .25rem;
            margin-bottom: 1rem;
        }
        .small-box {
            border-radius: .25rem;
            box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
            position: relative;
            display: block;
            margin-bottom: 20px;
        }
        .small-box>.inner {
            padding: 10px;
        }
        .small-box h3 {
            font-size: 2.2rem;
            font-weight: 700;
            margin: 0 0 10px;
            padding: 0;
            white-space: nowrap;
        }
        .small-box p {
            font-size: 1rem;
        }
        .small-box .icon {
            color: rgba(0,0,0,.15);
            z-index: 0;
        }
        .small-box .icon>i {
            font-size: 70px;
            position: absolute;
            right: 15px;
            top: 15px;
            transition: all .3s linear;
        }
        .card-header {
            font-weight: 600;
        }
        .funnel-step {
            transition: all 0.3s ease;
        }
        .funnel-step:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        .badge-lg {
            font-size: 1.1rem;
            padding: 0.5rem 0.8rem;
        }
        #clienteSelector {
            background: rgba(255,255,255,0.9);
            border: 1px solid rgba(255,255,255,0.3);
            color: #343a40;
            font-weight: 500;
        }
        #clienteSelector option {
            background: #fff;
            color: #343a40;
        }
        .brand-link {
            border-bottom: 1px solid #4b545c;
        }
        .cliente-info {
            padding: 10px 15px;
            background: rgba(0,0,0,0.1);
            margin: 10px 15px;
            border-radius: 5px;
        }
        
        /* Cursor pointer en tabs */
        .nav-tabs .nav-link {
            cursor: pointer;
        }
        
        /* Dark Mode Enhancements */
        .dark-mode {
            background-color: #1a1d24 !important;
        }
        
        .dark-mode .main-header {
            background-color: #1f2937 !important;
            border-bottom: 1px solid #374151 !important;
        }
        
        .dark-mode .main-sidebar {
            background-color: #111827 !important;
        }
        
        .dark-mode .content-wrapper {
            background-color: #1a1d24 !important;
            color: #e5e7eb !important;
        }
        
        .dark-mode .card {
            background-color: #1f2937 !important;
            border-color: #374151 !important;
            color: #e5e7eb !important;
        }
        
        .dark-mode .card-header {
            background-color: #111827 !important;
            border-bottom-color: #374151 !important;
            color: #f3f4f6 !important;
        }
        
        .dark-mode .table {
            color: #e5e7eb !important;
            border-color: #374151 !important;
            background-color: transparent !important;
        }
        
        .dark-mode .table thead th {
            background-color: #111827 !important;
            border-color: #374151 !important;
            color: #f3f4f6 !important;
        }
        
        .dark-mode .table tbody {
            background-color: #1f2937 !important;
        }
        
        .dark-mode .table tbody tr {
            background-color: #1f2937 !important;
            border-color: #374151 !important;
        }
        
        .dark-mode .table td,
        .dark-mode .table th {
            border-color: #374151 !important;
            background-color: transparent !important;
            color: #e5e7eb !important;
        }
        
        .dark-mode .table tbody td,
        .dark-mode .table tbody th {
            color: #f3f4f6 !important;
        }
        
        .dark-mode .table a {
            color: #60a5fa !important;
        }
        
        .dark-mode .table .badge {
            color: #fff !important;
        }
        
        .dark-mode .table-striped tbody tr:nth-of-type(odd) {
            background-color: #1a1d24 !important;
        }
        
        .dark-mode .table-striped tbody tr:nth-of-type(even) {
            background-color: #1f2937 !important;
        }
        
        .dark-mode .table-hover tbody tr:hover {
            background-color: rgba(99,102,241,0.15) !important;
        }
        
        .dark-mode .table-bordered {
            border-color: #374151 !important;
        }
        
        .dark-mode .table-bordered td,
        .dark-mode .table-bordered th {
            border-color: #374151 !important;
        }
        
        .dark-mode .modal-content {
            background-color: #1f2937 !important;
            border-color: #374151 !important;
        }
        
        .dark-mode .modal-header {
            border-bottom-color: #374151 !important;
        }
        
        .dark-mode .modal-footer {
            border-top-color: #374151 !important;
        }
        
        .dark-mode .form-control {
            background-color: #111827 !important;
            border-color: #374151 !important;
            color: #e5e7eb !important;
        }
        
        .dark-mode .form-control:focus {
            background-color: #1f2937 !important;
            border-color: #6366f1 !important;
            color: #e5e7eb !important;
        }
        
        .dark-mode .form-select {
            background-color: #111827 !important;
            border-color: #374151 !important;
            color: #e5e7eb !important;
        }
        
        /* Dark Mode - Date Inputs & Calendar */
        .dark-mode input[type="date"] {
            background-color: #111827 !important;
            border-color: #374151 !important;
            color: #e5e7eb !important;
            color-scheme: dark;
        }
        
        .dark-mode input[type="date"]:focus {
            background-color: #1f2937 !important;
            border-color: #6366f1 !important;
        }
        
        .dark-mode input[type="date"]::-webkit-calendar-picker-indicator {
            filter: invert(1);
            cursor: pointer;
        }
        
        .dark-mode .fa-calendar {
            color: #e5e7eb !important;
        }
        
        .dark-mode .form-label {
            color: #e5e7eb !important;
        }
        
        .dark-mode .alert {
            border-color: #374151 !important;
        }
        
        .dark-mode .alert-info {
            background-color: #1e3a5f !important;
            color: #93c5fd !important;
        }
        
        .dark-mode .alert-success {
            background-color: #064e3b !important;
            color: #86efac !important;
        }
        
        .dark-mode .alert-warning {
            background-color: #78350f !important;
            color: #fcd34d !important;
        }
        
        .dark-mode .alert-danger {
            background-color: #7f1d1d !important;
            color: #fca5a5 !important;
        }
        
        .dark-mode .small-box {
            background-color: #1f2937 !important;
        }
        
        .dark-mode .info-box {
            background-color: #1f2937 !important;
            color: #e5e7eb !important;
        }
        
        .dark-mode .nav-pills .nav-link {
            color: #9ca3af !important;
        }
        
        .dark-mode .nav-pills .nav-link.active {
            background-color: #6366f1 !important;
        }
        
        .dark-mode code {
            background-color: #111827 !important;
            color: #a78bfa !important;
        }
        
        .dark-mode .text-muted {
            color: #9ca3af !important;
        }
        
        /* Toggle Switch Styling */
        .dark-mode-toggle {
            position: relative;
            width: 50px;
            height: 24px;
            background-color: #cbd5e1;
            border-radius: 12px;
            cursor: pointer;
            transition: background-color 0.3s;
            display: inline-block;
        }
        
        .dark-mode-toggle::before {
            content: '';
            position: absolute;
            top: 2px;
            left: 2px;
            width: 20px;
            height: 20px;
            background-color: white;
            border-radius: 50%;
            transition: transform 0.3s;
        }
        
        .dark-mode-toggle.active {
            background-color: #6366f1;
        }
        
        .dark-mode-toggle.active::before {
            transform: translateX(26px);
        }
        
        .dark-mode-icon {
            font-size: 18px;
            margin-right: 10px;
            color: #6b7280;
            transition: color 0.3s;
        }
        
        .dark-mode .dark-mode-icon {
            color: #fbbf24;
        }        
        /* Dark Mode - Badges */
        .dark-mode .badge-info {
            background-color: #1e3a8a !important;
            color: #93c5fd !important;
        }
        
        .dark-mode .badge-success {
            background-color: #065f46 !important;
            color: #6ee7b7 !important;
        }
        
        .dark-mode .badge-warning {
            background-color: #92400e !important;
            color: #fde68a !important;
        }
        
        .dark-mode .badge-danger {
            background-color: #991b1b !important;
            color: #fca5a5 !important;
        }
        
        .dark-mode .badge-primary {
            background-color: #4338ca !important;
            color: #c7d2fe !important;
        }
        
        .dark-mode .badge-secondary {
            background-color: #374151 !important;
            color: #d1d5db !important;
        }
        
        /* Dark Mode - Buttons */
        .dark-mode .btn-primary {
            background-color: #4f46e5 !important;
            border-color: #4f46e5 !important;
        }
        
        .dark-mode .btn-primary:hover {
            background-color: #4338ca !important;
            border-color: #4338ca !important;
        }
        
        .dark-mode .btn-success {
            background-color: #059669 !important;
            border-color: #059669 !important;
        }
        
        .dark-mode .btn-success:hover {
            background-color: #047857 !important;
            border-color: #047857 !important;
        }
        
        .dark-mode .btn-danger {
            background-color: #dc2626 !important;
            border-color: #dc2626 !important;
        }
        
        .dark-mode .btn-secondary {
            background-color: #4b5563 !important;
            border-color: #4b5563 !important;
        }
        
        /* Dark Mode - Small Boxes */
        .dark-mode .small-box.bg-info {
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%) !important;
        }
        
        .dark-mode .small-box.bg-success {
            background: linear-gradient(135deg, #065f46 0%, #10b981 100%) !important;
        }
        
        .dark-mode .small-box.bg-warning {
            background: linear-gradient(135deg, #92400e 0%, #f59e0b 100%) !important;
        }
        
        .dark-mode .small-box.bg-danger {
            background: linear-gradient(135deg, #991b1b 0%, #ef4444 100%) !important;
        }
        
        .dark-mode .small-box .icon {
            color: rgba(255,255,255,0.15) !important;
        }
        
        /* Dark Mode - Nav Pills */
        .dark-mode .nav-tabs .nav-link {
            color: #9ca3af !important;
            border-color: #374151 !important;
            cursor: pointer;
        }
        
        .dark-mode .nav-tabs .nav-link.active {
            background-color: #1f2937 !important;
            border-color: #374151 !important;
            color: #f3f4f6 !important;
        }
        
        /* Dark Mode - Dropdown */
        .dark-mode .dropdown-menu {
            background-color: #1f2937 !important;
            border-color: #374151 !important;
        }
        
        .dark-mode .dropdown-item {
            color: #e5e7eb !important;
        }
        
        .dark-mode .dropdown-item:hover {
            background-color: #374151 !important;
        }
        
        /* Dark Mode - Pagination */
        .dark-mode .page-link {
            background-color: #1f2937 !important;
            border-color: #374151 !important;
            color: #9ca3af !important;
        }
        
        .dark-mode .page-item.active .page-link {
            background-color: #6366f1 !important;
            border-color: #6366f1 !important;
        }
        
        /* Dark Mode - Cliente Selector */
        .dark-mode #clienteSelector {
            background-color: #1f2937 !important;
            color: #e5e7eb !important;
            border-color: #4b5563 !important;
        }
        
        /* Dark Mode - Sidebar Enhancements */
        .dark-mode .sidebar {
            background: transparent !important;
        }
        
        .dark-mode .nav-sidebar .nav-link {
            color: #9ca3af !important;
        }
        
        .dark-mode .nav-sidebar .nav-link:hover {
            background-color: rgba(99, 102, 241, 0.1) !important;
            color: #c7d2fe !important;
        }
        
        .dark-mode .nav-sidebar .nav-link.active {
            background-color: #6366f1 !important;
            color: #ffffff !important;
        }
        
        .dark-mode .nav-header {
            color: #6b7280 !important;
        }
        
        /* Dark Mode - Footer */
        .dark-mode .main-footer {
            background-color: #1f2937 !important;
            border-top-color: #374151 !important;
            color: #9ca3af !important;
        }
        
        /* Smooth Transitions */
        body, .main-header, .main-sidebar, .content-wrapper, .card, .form-control, .btn {
            transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease !important;
        }
    </style>
    
    <script>
        // Configuración global - Path base del sistema
        <?php 
        require_once __DIR__ . '/../../config.php';
        ?>
        window.APP_CONFIG = {
            basePath: '<?php echo BASE_PATH; ?>',
            baseUrl: '<?php echo BASE_URL; ?>'
        };
    </script>
</head>
<body class="hold-transition sidebar-mini"
<div class="wrapper">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="index.php" class="nav-link">Dashboard Marketing</a>
            </li>
        </ul>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
            <!-- Dark Mode Toggle -->
            <li class="nav-item d-flex align-items-center mr-3">
                <i class="fas fa-moon dark-mode-icon" id="darkModeIcon"></i>
                <div class="dark-mode-toggle" id="darkModeToggle"></div>
            </li>
            <li class="nav-item">
                <span class="nav-link">
                    <i class="far fa-building"></i>
                    <span id="clienteActualNombre" class="ml-1 font-weight-bold">Sin cliente</span>
                </span>
            </li>
            <li class="nav-item">
                <span class="nav-link">
                    <i class="far fa-user"></i>
                    <span class="ml-1"><?php echo $_SESSION['usuario_nombre'] ?? 'Usuario'; ?></span>
                </span>
            </li>
        </ul>
    </nav>
