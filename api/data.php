<?php
try {
    if (!file_exists('../backends/connection-pdo.php')) {
        throw new Exception();
    } else {
        require_once('../backends/connection-pdo.php');
    }
} catch (Exception $e) {
    echo json_encode(array());
    exit();
}

if (isset($_POST['action']) && $_POST['action'] == 'get_doctors' && isset($_POST['dept_id'])) {
    $dept_id = intval($_POST['dept_id']);
    $sql = "SELECT id, name, fee, available_start, available_end FROM doctors WHERE dept_id = ?";
    $query = $pdoconn->prepare($sql);
    $query->execute([$dept_id]);
    $doctors = $query->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($doctors);
} else if (isset($_POST['action']) && $_POST['action'] == 'get_patient_history' && isset($_POST['email'])) {
    $email = $_POST['email'];
    $sql = "SELECT p.*, d.name as doctor_name FROM prescriptions p JOIN doctors d ON p.doctor_id = d.id WHERE p.patient_email = ? ORDER BY p.id DESC";
    $query = $pdoconn->prepare($sql);
    $query->execute([$email]);
    $history = $query->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($history);
} else {
    echo json_encode(array());
}
?>
