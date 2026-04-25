<?php
try {
    if (!file_exists('connection-pdo.php')) {
        throw new Exception();
    } else {
        require_once('connection-pdo.php');
    }
} catch (Exception $e) {
    echo json_encode(['code' => "0", 'msg' => "Server connection failed!"]);
    exit();
}

if (!isset($_POST['name']) || !isset($_POST['email']) || !isset($_POST['password'])) {
    echo json_encode(['code' => "0", 'msg' => "Missing required fields!"]);
    exit();
}

$regex_email = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/';
$regex_name = '/^[a-zA-Z\s.]+$/';
$regex_password = '/^[a-zA-Z0-9!@#%^&*()_+-=]+$/';

if (!preg_match($regex_name, $_POST['name'])) {
    echo json_encode(['code' => "0", 'msg' => "Invalid name format!"]);
    exit();
}
if (!preg_match($regex_email, $_POST['email'])) {
    echo json_encode(['code' => "0", 'msg' => "Invalid email format!"]);
    exit();
}
if (!preg_match($regex_password, $_POST['password'])) {
    echo json_encode(['code' => "0", 'msg' => "Invalid characters in password!"]);
    exit();
}
if (strlen($_POST['password']) < 5) {
    echo json_encode(['code' => "0", 'msg' => "Password must be at least 5 characters!"]);
    exit();
}

date_default_timezone_set("Asia/Dhaka");
$name = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['password'];
$role = 'patient';
$timestamp = date("d:m:Y h:i:sa");

$sql = "SELECT * FROM users WHERE email=?";
$query = $pdoconn->prepare($sql);
$query->execute([$email]);
$existing = $query->fetchAll(PDO::FETCH_ASSOC);

if (count($existing) > 0) {
    echo json_encode(['code' => "0", 'msg' => "Email already registered!"]);
    exit();
}

// Calculate new patient ID (starts at 100)
$sql_id = "SELECT MAX(id) as max_id FROM users WHERE role = 'patient'";
$query_id = $pdoconn->query($sql_id);
$row_id = $query_id->fetch(PDO::FETCH_ASSOC);
$new_id = ($row_id['max_id'] && $row_id['max_id'] >= 100) ? $row_id['max_id'] + 1 : 100;

$sql = "INSERT INTO users(id, name, email, password, role, timestamp) VALUES(?,?,?,?,?,?)";
$query = $pdoconn->prepare($sql);
if ($query->execute([$new_id, $name, $email, $password, $role, $timestamp])) {
    echo json_encode(['code' => "1", 'msg' => "Registration successful! Your ID is $new_id. Please login."]);
} else {
    echo json_encode(['code' => "0", 'msg' => "Registration failed. Please try again."]);
}
?>
