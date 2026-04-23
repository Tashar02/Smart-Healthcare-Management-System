<?php
try {
    if (!file_exists('connection-pdo.php')) {
        throw new Exception();
    } else {
        require_once('connection-pdo.php');
    }
} catch (Exception $e) {
    $arr = array('code' => "0", 'msg' => "There were some problem in the Server! Try after some time!");
    echo json_encode($arr);
    exit();
}

if (!isset($_POST['patient_name']) || !isset($_POST['patient_email']) || !isset($_POST['doctor_id']) || !isset($_POST['dept_id']) || !isset($_POST['appointment_date']) || !isset($_POST['appointment_time'])) {
    $arr = array('code' => "0", 'msg' => "Invalid POST variable keys! Refresh the page!");
    echo json_encode($arr);
    exit();
}

$regex_email = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/';
$regex_name = '/^[(A-Z)?(a-z)?(0-9)?\s*]+$/';

if (!preg_match($regex_name, $_POST['patient_name']) || !preg_match($regex_email, $_POST['patient_email'])) {
    $arr = array('code' => "0", 'msg' => "Whoa! Invalid Inputs!");
    echo json_encode($arr);
    exit();
} else {
    $patient_name = $_POST['patient_name'];
    $patient_email = $_POST['patient_email'];
    $doctor_id = intval($_POST['doctor_id']);
    $dept_id = intval($_POST['dept_id']);
    $appointment_date = $_POST['appointment_date'];
    $appointment_time = $_POST['appointment_time'];
    $notes = isset($_POST['notes']) ? $_POST['notes'] : '';

    $sql = "INSERT INTO appointments(patient_name, patient_email, doctor_id, dept_id, appointment_date, appointment_time, notes, status) VALUES(?,?,?,?,?,?,?, 'pending')";
    $query = $pdoconn->prepare($sql);
    if ($query->execute([$patient_name, $patient_email, $doctor_id, $dept_id, $appointment_date, $appointment_time, $notes])) {
        $arr = array('code' => "1", 'msg' => "Appointment booked successfully! We will contact you shortly.");
        echo json_encode($arr);
    } else {
        $arr = array('code' => "0", 'msg' => "There were some problem in the server! Please try again after some time!");
        echo json_encode($arr);
    }
}
?>
