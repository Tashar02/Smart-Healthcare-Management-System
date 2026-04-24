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

if (strtotime($appointment_date) < strtotime(date('Y-m-d'))) {
    echo json_encode(['code' => "0", 'msg' => "You cannot book an appointment in the past!"]);
    exit();
}

$sql_doc = "SELECT available_start, available_end FROM doctors WHERE id=?";
$query_doc = $pdoconn->prepare($sql_doc);
$query_doc->execute([$doctor_id]);
$doc_avail = $query_doc->fetch(PDO::FETCH_ASSOC);

if (!$doc_avail) {
    echo json_encode(['code' => "0", 'msg' => "Doctor not found!"]);
    exit();
}

$current_date = $appointment_date;
$current_time = $appointment_time;

while (true) {
    // Check if time is within availability
    if ($current_time < $doc_avail['available_start'] || $current_time >= $doc_avail['available_end']) {
        // Move to next day
        $current_date = date('Y-m-d', strtotime($current_date . ' +1 day'));
        $current_time = $doc_avail['available_start'];
        continue;
    }

    // Check if slot is taken
    $sql_check = "SELECT COUNT(*) FROM appointments WHERE doctor_id=? AND appointment_date=? AND appointment_time=? AND status != 'cancelled'";
    $query_check = $pdoconn->prepare($sql_check);
    $query_check->execute([$doctor_id, $current_date, $current_time]);
    
    if ($query_check->fetchColumn() == 0) {
        // Slot is free!
        break;
    }
    
    // Slot taken, try next 20 mins
    $timestamp = strtotime($current_time);
    $timestamp += 20 * 60;
    $current_time = date("H:i", $timestamp);
}

$final_msg = "Appointment booked successfully!";
if ($current_date != $appointment_date || $current_time != $appointment_time) {
    $final_msg = "The requested time was filled. We have scheduled you for the nearest available time: " . $current_date . " at " . $current_time;
}

$sql = "INSERT INTO appointments(patient_name, patient_email, doctor_id, dept_id, appointment_date, appointment_time, notes, status) VALUES(?,?,?,?,?,?,?, 'pending')";
$query = $pdoconn->prepare($sql);
if ($query->execute([$patient_name, $patient_email, $doctor_id, $dept_id, $current_date, $current_time, $notes])) {
    echo json_encode(['code' => "1", 'msg' => $final_msg]);
} else {
    echo json_encode(['code' => "0", 'msg' => "Booking failed. Please try again."]);
}
?>
