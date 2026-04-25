<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'patient') {
    $arr = array('code' => "0", 'msg' => "Unauthorized access!");
    echo json_encode($arr);
    exit();
}

try {
    require_once('connection-pdo.php');
} catch (Exception $e) {
    $arr = array('code' => "0", 'msg' => "Database connection failed!");
    echo json_encode($arr);
    exit();
}

if (!isset($_POST['bill_id']) || !isset($_POST['payment_method'])) {
    $arr = array('code' => "0", 'msg' => "Invalid request!");
    echo json_encode($arr);
    exit();
}

$bill_id = $_POST['bill_id'];
$payment_method = $_POST['payment_method'];
$patient_id = $_SESSION['user_id'];
$paid_at = date("Y-m-d H:i:s");

// Verify that the bill belongs to the logged-in patient and is pending
$sql_verify = "SELECT id FROM billings WHERE id = ? AND patient_id = ? AND status = 'pending'";
$query_verify = $pdoconn->prepare($sql_verify);
$query_verify->execute([$bill_id, $patient_id]);

if ($query_verify->rowCount() > 0) {
    $sql_update = "UPDATE billings SET status = 'completed', payment_method = ?, paid_at = ? WHERE id = ?";
    $query_update = $pdoconn->prepare($sql_update);
    
    if ($query_update->execute([$payment_method, $paid_at, $bill_id])) {
        $arr = array('code' => "1", 'msg' => "Payment successful!");
        echo json_encode($arr);
    } else {
        $arr = array('code' => "0", 'msg' => "Payment failed! Please try again.");
        echo json_encode($arr);
    }
} else {
    $arr = array('code' => "0", 'msg' => "Invalid bill or already paid!");
    echo json_encode($arr);
}
?>
