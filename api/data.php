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
    $sql = "SELECT d.id, u.name, d.fee, d.available_start, d.available_end
            FROM doctors d
            JOIN users u ON d.id = u.id
            WHERE d.dept_id = ?";
    $query = $pdoconn->prepare($sql);
    $query->execute([$dept_id]);
    $doctors = $query->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($doctors);
} else if (isset($_POST['action']) && $_POST['action'] == 'get_patient_history' && isset($_POST['patient_id'])) {
    $patient_id = intval($_POST['patient_id']);
    $sql = "SELECT a.appointment_date, a.notes, d.id as doctor_id, u_doc.name as doctor_name, p.medications, p.instructions 
            FROM appointments a 
            JOIN users u_doc ON a.doctor_id = u_doc.id
            JOIN doctors d ON a.doctor_id = d.id 
            LEFT JOIN prescriptions p ON (a.patient_id = p.patient_id AND a.doctor_id = p.doctor_id AND DATE(p.created_at) = a.appointment_date)
            WHERE a.patient_id = ? AND a.status = 'completed' 
            ORDER BY a.id DESC";
    $query = $pdoconn->prepare($sql);
    $query->execute([$patient_id]);
    $history = $query->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($history);
} else {
    echo json_encode(array());
}
?>
