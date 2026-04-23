<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
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

if (!isset($_POST['email']) || !isset($_POST['password'])) {
    echo json_encode(['code' => "0", 'msg' => "Invalid inputs!"]);
    exit();
}

$regex_email = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/';
$regex_password = '/^[(A-Z)?(a-z)?(0-9)?!?@?#?-?_?%?]+$/';

if (!preg_match($regex_email, $_POST['email']) || !preg_match($regex_password, $_POST['password'])) {
    echo json_encode(['code' => "0", 'msg' => "Invalid inputs!"]);
    exit();
}

$email = $_POST['email'];
$password = $_POST['password'];
$sql = "SELECT * FROM users WHERE email=?";
$query = $pdoconn->prepare($sql);
$query->execute([$email]);
$users = $query->fetchAll(PDO::FETCH_ASSOC);

if (count($users) > 0) {
    $user = $users[0];
    if ($user['password'] == $password) {
        $_SESSION['user'] = explode(" ", $user['name'])[0];
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_email'] = $email;
        $_SESSION['role'] = $user['role'];
        echo json_encode(['code' => "1", 'msg' => "Logged In Successfully!", 'role' => $user['role']]);
    } else {
        echo json_encode(['code' => "0", 'msg' => "Invalid Password!"]);
    }
} else {
    echo json_encode(['code' => "0", 'msg' => "No account found with this email!"]);
}
?>
