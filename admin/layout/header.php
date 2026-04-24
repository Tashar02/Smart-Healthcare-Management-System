<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['admin'])) {
    header('location: login-admin.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../css/form-style.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.css">
    <link rel="stylesheet" href="../css/healthcare-theme.css">
    <title>Admin Panel | HealthCare MS</title>
    <style>
        :root {
            --admin-primary: #e65100; /* Deep Orange 900 */
            --admin-primary-light: #fb8c00; /* Orange 600 */
            --admin-primary-dark: #bf360c;
            --admin-bg: #fff3e0;
        }
        
        .admin-theme-bg { background: var(--admin-primary) !important; }
        .admin-theme-text { color: var(--admin-primary) !important; }
        .admin-btn { background: var(--admin-primary-light) !important; }
        .admin-btn:hover { background: var(--admin-primary-dark) !important; }
        .admin-gradient { background: linear-gradient(135deg, var(--admin-primary), var(--admin-primary-light)) !important; }
        
        /* Overrides */
        nav { background-color: var(--admin-primary) !important; }
        .sidebar-long .brand-logo { color: var(--admin-primary) !important; font-weight: 700; }
        .sidebar-long ul.sidebar-links li a:hover {
            color: var(--admin-primary) !important;
            border-left: 5px solid var(--admin-primary) !important;
            background: var(--admin-bg) !important;
        }
        .section.white-text.center { background: var(--admin-primary) !important; }
        
        /* Dashboard Cards */
        .metric-card {
            padding: 30px;
            border-radius: 12px;
            color: white;
            transition: transform 0.3s ease;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .metric-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(230, 81, 0, 0.3);
        }
        .metric-card h3 { margin: 0; font-weight: 700; font-size: 3rem; }
        .metric-card p { margin: 10px 0 0 0; font-size: 1.2rem; text-transform: uppercase; letter-spacing: 1px; }
        .metric-card i { font-size: 4rem; opacity: 0.8; }
    </style>
</head>
<body>
