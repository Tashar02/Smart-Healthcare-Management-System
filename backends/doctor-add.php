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

if (!isset($_POST['name']) || !isset($_POST['email']) || !isset($_POST['password']) || !isset($_POST['dept_id']) || !isset($_POST['specialization']) || !isset($_POST['fee']) || !isset($_POST['available_start']) || !isset($_POST['available_end'])) {
    $_SESSION['msg'] = 'Invalid form submission!';
    header('location: ../admin/doctor-add.php');
    exit();
}

$name = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['password'];
$dept_id = intval($_POST['dept_id']);
$specialization = $_POST['specialization'];
$fee = intval($_POST['fee']);
$available_start = $_POST['available_start'];
$available_end = $_POST['available_end'];

// Check if email already exists in users table
$sql_check = "SELECT * FROM users WHERE email=?";
$query_check = $pdoconn->prepare($sql_check);
$query_check->execute([$email]);
if ($query_check->rowCount() > 0) {
    $_SESSION['msg'] = 'Email already exists in the system!';
    header('location: ../admin/doctor-add.php');
    exit();
}

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

// Insert into doctors table
$sql = "INSERT INTO doctors(dept_id, name, email, image, specialization, fee, available_start, available_end) VALUES(?,?,?,?,?,?,?,?)";
$query = $pdoconn->prepare($sql);
if ($query->execute([$dept_id, $name, $email, $image, $specialization, $fee, $available_start, $available_end])) {
    // Also insert into users table for login
    date_default_timezone_set("Asia/Dhaka");
    $timestamp = date("d:m:Y h:i:sa");
    $sql_user = "INSERT INTO users(name, email, password, role, timestamp) VALUES(?,?,?,'doctor',?)";
    $query_user = $pdoconn->prepare($sql_user);
    $query_user->execute([$name, $email, $password, $timestamp]);
    
    $_SESSION['msg'] = 'Doctor added successfully!';
} else {
    $_SESSION['msg'] = 'Failed to add doctor!';
}
header('location: ../admin/doctor-list.php');
exit();
?>
