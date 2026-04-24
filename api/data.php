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
    $sql = "SELECT a.appointment_date, a.notes, d.name as doctor_name, p.medications, p.instructions 
            FROM appointments a 
            JOIN doctors d ON a.doctor_id = d.id 
            LEFT JOIN prescriptions p ON (a.patient_email = p.patient_email AND a.doctor_id = p.doctor_id AND DATE(p.created_at) = a.appointment_date)
            WHERE a.patient_email = ? AND a.status = 'completed' 
            ORDER BY a.id DESC";
    $query = $pdoconn->prepare($sql);
    $query->execute([$email]);
    $history = $query->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($history);
} else {
    echo json_encode(array());
}
?>
