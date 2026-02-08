<?php
// Include config first
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?> - PMB Universitas</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        :root {
            --primary: #3b82f6;
            --primary-dark: #1d4ed8;
            --secondary: #10b981;
            --accent: #f59e0b;
            --danger: #ef4444;
            --light: #f8fafc;
            --dark: #1e293b;
            --gray-100: #f1f5f9;
            --gray-200: #e2e8f0;
            --gray-300: #cbd5e1;
        }
        
        * {
            font-family: 'Segoe UI', 'Inter', sans-serif;
        }
        
        body {
            background: var(--light);
            color: var(--dark);
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }
        
        /* Admin Layout */
        .admin-container {
            display: flex;
            min-height: 100vh;
        }
        
        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: 260px;
            transition: margin-left 0.3s;
            width: calc(100% - 260px);
            min-height: 100vh;
        }
        
        .content-area {
            padding: 30px;
            background: var(--light);
            min-height: calc(100vh - 70px);
        }
        
        /* Topbar */
        .topbar {
            background: white;
            padding: 15px 30px;
            border-bottom: 1px solid var(--gray-200);
            position: sticky;
            top: 0;
            z-index: 999;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            width: 100%;
        }
        
        /* Responsive */
        @media (max-width: 992px) {
            .main-content {
                margin-left: 0;
                width: 100%;
            }
        }
        
        .mobile-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: var(--dark);
            cursor: pointer;
        }
        
        @media (max-width: 992px) {
            .mobile-toggle {
                display: block !important;
            }
        }
        
        /* Card Styles */
        .card {
            border: 1px solid var(--gray-200);
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            background: white;
            margin-bottom: 20px;
        }
        
        .card-header {
            background: white;
            border-bottom: 1px solid var(--gray-200);
            padding: 15px 20px;
            border-radius: 10px 10px 0 0;
        }
        
        /* Table Styles */
        .table th {
            background: var(--gray-100);
            color: var(--dark);
            font-weight: 600;
            padding: 12px 15px;
            border-bottom: 2px solid var(--gray-200);
        }
        
        .table td {
            padding: 12px 15px;
            vertical-align: middle;
            border-color: var(--gray-200);
        }
        
        /* Badge Styles */
        .badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-weight: 500;
            font-size: 0.85rem;
        }
        
        .bg-success { background-color: var(--secondary) !important; }
        .bg-warning { background-color: var(--accent) !important; }
        .bg-danger { background-color: var(--danger) !important; }
        .bg-info { background-color: var(--primary) !important; }
        .bg-primary { background-color: var(--primary) !important; }
        
        /* Button Styles */
        .btn {
            border-radius: 8px;
            padding: 8px 20px;
            font-weight: 500;
            transition: all 0.2s;
        }
        
        .btn-sm {
            padding: 6px 12px;
            font-size: 0.875rem;
        }
        
        /* Form Styles */
        .form-control, .form-select {
            border: 1px solid var(--gray-300);
            border-radius: 8px;
            padding: 10px 15px;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
    </style>
</head>
<body>
    <div class="admin-container">