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
    header('location: ../admin/department-add.php');
    exit();
}

if (!isset($_POST['dept_name']) || !isset($_POST['short_desc']) || !isset($_POST['long_desc'])) {
    $_SESSION['msg'] = 'Invalid form submission!';
    header('location: ../admin/department-add.php');
    exit();
}

$dept_name = $_POST['dept_name'];
$short_desc = $_POST['short_desc'];
$long_desc = $_POST['long_desc'];

$sql = "INSERT INTO departments(dept_name, short_desc, long_desc) VALUES(?,?,?)";
$query = $pdoconn->prepare($sql);
if ($query->execute([$dept_name, $short_desc, $long_desc])) {
    $_SESSION['msg'] = 'Department added successfully!';
} else {
    $_SESSION['msg'] = 'Failed to add department!';
}
header('location: ../admin/department-list.php');
exit();
?>
