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

if (!isset($_POST['name']) || !isset($_POST['dept_id']) || !isset($_POST['specialization']) || !isset($_POST['fee']) || !isset($_POST['available_start']) || !isset($_POST['available_end'])) {
    $_SESSION['msg'] = 'Invalid form submission!';
    header('location: ../admin/doctor-add.php');
    exit();
}

$name = $_POST['name'];
$dept_id = intval($_POST['dept_id']);
$specialization = $_POST['specialization'];
$fee = intval($_POST['fee']);
$available_start = $_POST['available_start'];
$available_end = $_POST['available_end'];

$image = 'default-doctor.jpg';
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $image = basename($_FILES['image']['name']);
    $target_dir = "../images/";
    $target_file = $target_dir . $image;
    if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
        $image = $image;
    } else {
        $image = 'default-doctor.jpg';
    }
}

$sql = "INSERT INTO doctors(dept_id, name, image, specialization, fee, available_start, available_end) VALUES(?,?,?,?,?,?,?)";
$query = $pdoconn->prepare($sql);
if ($query->execute([$dept_id, $name, $image, $specialization, $fee, $available_start, $available_end])) {
    $_SESSION['msg'] = 'Doctor added successfully!';
} else {
    $_SESSION['msg'] = 'Failed to add doctor!';
}
header('location: ../admin/doctor-list.php');
exit();
?>
