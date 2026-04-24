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

if (!isset($_POST['patient_name']) || !isset($_POST['patient_email']) || !isset($_POST['doctor_id']) || !isset($_POST['dept_id']) || !isset($_POST['appointment_date']) || !isset($_POST['appointment_time'])) {
    echo json_encode(['code' => "0", 'msg' => "Invalid inputs!"]);
    exit();
}

$regex_email = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/';
$regex_name = '/^[(A-Z)?(a-z)?(0-9)?\s*]+$/';

if (!preg_match($regex_name, $_POST['patient_name']) || !preg_match($regex_email, $_POST['patient_email'])) {
    echo json_encode(['code' => "0", 'msg' => "Invalid name or email!"]);
    exit();
}

$patient_name = $_POST['patient_name'];
$patient_email = $_POST['patient_email'];
$doctor_id = intval($_POST['doctor_id']);
$dept_id = intval($_POST['dept_id']);
$appointment_date = $_POST['appointment_date'];
$appointment_time = $_POST['appointment_time'];
$notes = isset($_POST['notes']) ? $_POST['notes'] : '';

$sql_doc = "SELECT available_start, available_end FROM doctors WHERE id=?";
$query_doc = $pdoconn->prepare($sql_doc);
$query_doc->execute([$doctor_id]);
$doc_avail = $query_doc->fetch(PDO::FETCH_ASSOC);

if (!$doc_avail || $appointment_time < $doc_avail['available_start'] || $appointment_time > $doc_avail['available_end']) {
    echo json_encode(['code' => "0", 'msg' => "Selected time is outside doctor's availability!"]);
    exit();
}

$sql_dup = "SELECT COUNT(*) FROM appointments WHERE patient_email=? AND doctor_id=? AND appointment_date=? AND appointment_time=?";
$query_dup = $pdoconn->prepare($sql_dup);
$query_dup->execute([$patient_email, $doctor_id, $appointment_date, $appointment_time]);
if ($query_dup->fetchColumn() > 0) {
    echo json_encode(['code' => "0", 'msg' => "You already have an appointment with this doctor at this time!"]);
    exit();
}

$sql = "INSERT INTO appointments(patient_name, patient_email, doctor_id, dept_id, appointment_date, appointment_time, notes, status) VALUES(?,?,?,?,?,?,?, 'pending')";
$query = $pdoconn->prepare($sql);
if ($query->execute([$patient_name, $patient_email, $doctor_id, $dept_id, $appointment_date, $appointment_time, $notes])) {
    echo json_encode(['code' => "1", 'msg' => "Appointment booked successfully!"]);
} else {
    echo json_encode(['code' => "0", 'msg' => "Booking failed. Please try again."]);
}
?>
