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

if (!isset($_POST['name']) || !isset($_POST['email']) || !isset($_POST['password']) || !isset($_POST['role'])) {
    echo json_encode(['code' => "0", 'msg' => "Invalid inputs!"]);
    exit();
}

$regex_email = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/';
$regex_name = '/^[(A-Z)?(a-z)?(0-9)?\s*]+$/';
$regex_password = '/^[(A-Z)?(a-z)?(0-9)?!?@?#?-?_?%?]+$/';

if (!preg_match($regex_name, $_POST['name']) || !preg_match($regex_email, $_POST['email']) || !preg_match($regex_password, $_POST['password'])) {
    echo json_encode(['code' => "0", 'msg' => "Invalid inputs!"]);
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

$sql = "INSERT INTO users(name, email, password, role, timestamp) VALUES(?,?,?,?,?)";
$query = $pdoconn->prepare($sql);
if ($query->execute([$name, $email, $password, $role, $timestamp])) {
    echo json_encode(['code' => "1", 'msg' => "Registration successful! Please login."]);
} else {
    echo json_encode(['code' => "0", 'msg' => "Registration failed. Please try again."]);
}
?>
