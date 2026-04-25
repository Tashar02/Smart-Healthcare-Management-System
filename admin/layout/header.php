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
            --admin-primary: #ef6c00; /* Orange Darken-3 */
            --admin-primary-light: #ff9800; /* Orange */
            --admin-accent: #ffcc80; /* Orange Lighten-3 */
            --admin-bg: #fffbf2; /* Very light orange-tinted background */
            --admin-card-bg: #ffffff;
            --admin-text: #424242;
        }
        
        body {
            background-color: var(--admin-bg);
            color: var(--admin-text);
            font-family: 'Bree Serif', serif;
        }

        .admin-theme-bg { background: var(--admin-primary) !important; }
        .admin-theme-text { color: var(--admin-primary) !important; }
        .admin-btn { background: var(--admin-primary) !important; border-radius: 4px; text-transform: none; font-weight: 600; }
        .admin-btn:hover { background: var(--admin-primary-light) !important; }
        .admin-gradient { background: linear-gradient(135deg, var(--admin-primary), var(--admin-primary-light)) !important; }
        
        /* Overrides */
        nav { 
            background-color: white !important; 
            box-shadow: 0 2px 10px rgba(0,0,0,0.05) !important;
            height: 70px;
            line-height: 70px;
        }
        nav .nav-wrapper a { color: var(--admin-primary) !important; font-weight: 600; }
        nav .nav-wrapper ul li a { color: var(--admin-text) !important; transition: 0.3s; }
        nav .nav-wrapper ul li a:hover { background-color: var(--admin-bg); color: var(--admin-primary) !important; }
        
        .section.admin-theme-bg { 
            background: white !important; 
            color: var(--admin-primary) !important;
            padding: 30px 0 !important;
            border-bottom: 1px solid #eee;
        }
        .section.admin-theme-bg h4 { margin: 0; font-weight: 700; color: var(--admin-primary); }
        
        /* Modernized Alert Message */
        .admin-alert {
            padding: 12px 20px;
            margin: 10px 0;
            border-radius: 8px;
            background: #fff3e0;
            border-left: 5px solid var(--admin-primary);
            color: #e65100;
            font-weight: 600;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }

        /* Dashboard Cards */
        .metric-card {
            padding: 25px;
            border-radius: 15px;
            color: white;
            transition: all 0.3s ease;
            box-shadow: 0 4px 20px rgba(239, 108, 0, 0.15);
            margin: 15px auto; /* Auto horizontal margin for perfect centering */
            width: calc(100% - 20px); /* Leave 10px gap on each side */
            display: block;
        }
        .metric-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 30px rgba(239, 108, 0, 0.25);
        }
        .metric-card h3 { margin: 10px 0 0 0; font-weight: 700; font-size: 2.5rem; }
        .metric-card p { margin: 5px 0 0 0; font-size: 1rem; text-transform: uppercase; letter-spacing: 1px; font-weight: 600; opacity: 0.9; }
        .metric-card i { font-size: 3rem; }

        /* Table Styling */
        .card { border-radius: 12px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); border: none; }
        .card-content { padding: 30px !important; }
        table.striped > tbody > tr:nth-child(odd) { background-color: #fffaf0; }
        th { color: var(--admin-primary); text-transform: uppercase; font-size: 0.85rem; letter-spacing: 1px; }

        /* Sidebar/Layout Fixes */
        .sidebar-long { display: none !important; } /* Removing the out-of-place sidebar */
        #topnav { width: 100% !important; margin-left: 0 !important; }
    </style>
</head>
<body>
