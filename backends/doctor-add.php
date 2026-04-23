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
    header('location: ../admin/doctor-add.php');
    exit();
}

if (!isset($_POST['name']) || !isset($_POST['dept_id']) || !isset($_POST['specialization']) || !isset($_POST['fee']) || !isset($_FILES['image'])) {
    $_SESSION['msg'] = 'Invalid form submission!';
    header('location: ../admin/doctor-add.php');
    exit();
}

$name = $_POST['name'];
$dept_id = intval($_POST['dept_id']);
$specialization = $_POST['specialization'];
$fee = intval($_POST['fee']);
$image = $_FILES['image']['name'];
$tmp_image = $_FILES['image']['tmp_name'];

$target_dir = "../images/";
$target_file = $target_dir . basename($image);
move_uploaded_file($tmp_image, $target_file);

$sql = "INSERT INTO doctors(dept_id, name, image, specialization, fee) VALUES(?,?,?,?,?)";
$query = $pdoconn->prepare($sql);
if ($query->execute([$dept_id, $name, $image, $specialization, $fee])) {
    $_SESSION['msg'] = 'Doctor added successfully!';
} else {
    $_SESSION['msg'] = 'Failed to add doctor!';
}
header('location: ../admin/doctor-list.php');
exit();
?>
