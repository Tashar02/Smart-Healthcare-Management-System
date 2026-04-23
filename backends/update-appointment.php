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
    header('location: ../admin/appointment-list.php');
    exit();
}

if (!isset($_POST['id']) || !isset($_POST['status'])) {
    $_SESSION['msg'] = 'Invalid request!';
    header('location: ../admin/appointment-list.php');
    exit();
}

$id = intval($_POST['id']);
$status = $_POST['status'];

$sql = "UPDATE appointments SET status=? WHERE id=?";
$query = $pdoconn->prepare($sql);
if ($query->execute([$status, $id])) {
    $_SESSION['msg'] = 'Appointment status updated!';
} else {
    $_SESSION['msg'] = 'Failed to update status!';
}
header('location: ../admin/appointment-list.php');
exit();
?>
