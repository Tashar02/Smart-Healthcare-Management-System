<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('location: ../admin/login-admin.php');
    exit();
}
try {
    if (!file_exists('connection-pdo.php')) {
        throw new Exception();
    } else {
        require_once('connection-pdo.php');
    }
} catch (Exception $e) {
    $_SESSION['msg'] = 'Database connection failed!';
    header('location: ../admin/dashboard.php');
    exit();
}

if (!isset($_GET['type']) || !isset($_GET['id'])) {
    $_SESSION['msg'] = 'Invalid request!';
    header('location: ../admin/dashboard.php');
    exit();
}

$type = $_GET['type'];
$id = intval($_GET['id']);

if ($type == 'department') {
    $sql = "DELETE FROM departments WHERE id=?";
    $redirect = '../admin/department-list.php';
} elseif ($type == 'doctor') {
    $sql = "DELETE FROM doctors WHERE id=?";
    $redirect = '../admin/doctor-list.php';
} else {
    $_SESSION['msg'] = 'Invalid type!';
    header('location: ../admin/dashboard.php');
    exit();
}

$query = $pdoconn->prepare($sql);
if ($query->execute([$id])) {
    $_SESSION['msg'] = 'Record deleted successfully!';
} else {
    $_SESSION['msg'] = 'Failed to delete record!';
}
header('location: ' . $redirect);
exit();
?>
