<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'doctor') {
    $arr = array('code' => "0", 'msg' => "Unauthorized access!");
    echo json_encode($arr);
    exit();
}
try {
    if (!file_exists('connection-pdo.php')) {
        throw new Exception();
    } else {
        require_once('connection-pdo.php');
    }
} catch (Exception $e) {
    $arr = array('code' => "0", 'msg' => "Database connection failed!");
    echo json_encode($arr);
    exit();
}

if (!isset($_POST['patient_email']) || !isset($_POST['medications'])) {
    $arr = array('code' => "0", 'msg' => "Invalid form submission!");
    echo json_encode($arr);
    exit();
}

$regex_email = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/';
if (!preg_match($regex_email, $_POST['patient_email'])) {
    $arr = array('code' => "0", 'msg' => "Invalid patient email!");
    echo json_encode($arr);
    exit();
}

$doctor_id = $_SESSION['doctor_id'];
$patient_email = $_POST['patient_email'];
$medications = $_POST['medications'];
$instructions = isset($_POST['instructions']) ? $_POST['instructions'] : '';
$created_at = date("Y-m-d H:i:s");

$sql = "INSERT INTO prescriptions(doctor_id, patient_email, medications, instructions, created_at) VALUES(?,?,?,?,?)";
$query = $pdoconn->prepare($sql);
if ($query->execute([$doctor_id, $patient_email, $medications, $instructions, $created_at])) {
    // Mark the earliest pending/confirmed appointment as completed
    $sql_update = "UPDATE appointments SET status='completed' WHERE doctor_id=? AND patient_email=? AND status != 'completed' ORDER BY appointment_date ASC, appointment_time ASC LIMIT 1";
    $query_update = $pdoconn->prepare($sql_update);
    $query_update->execute([$doctor_id, $patient_email]);

    $arr = array('code' => "1", 'msg' => "Prescription saved successfully!");
    echo json_encode($arr);
} else {
    $arr = array('code' => "0", 'msg' => "Failed to save prescription!");
    echo json_encode($arr);
}
?>
