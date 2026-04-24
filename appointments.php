<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('location: index.php');
    exit();
}
require_once('backends/connection-pdo.php');
$doctor_id = isset($_GET['doctor_id']) ? intval($_GET['doctor_id']) : 0;
$selected_doctor = null;
if ($doctor_id > 0) {
    $sql = "SELECT d.*, dept.dept_name FROM doctors d JOIN departments dept ON d.dept_id = dept.id WHERE d.id = ?";
    $query = $pdoconn->prepare($sql);
    $query->execute([$doctor_id]);
    $selected_doctor = $query->fetch(PDO::FETCH_ASSOC);
}
$sql_depts = "SELECT * FROM departments";
$query_depts = $pdoconn->prepare($sql_depts);
$query_depts->execute();
$departments = $query_depts->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Book Appointment | HealthCare MS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Pacifico&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Bree+Serif&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/healthcare-theme.css">
</head>
<body>
    <?php require('chunks/login-modal.php'); ?>
    <?php require('chunks/register-modal.php'); ?>
    <?php require('chunks/info-modal.php'); ?>
    <?php require('chunks/navbar.php'); ?>

    <div class="section white-text center" style="background: #4a6a5c; margin-top: 20px;">
        <h4>Book Appointment</h4>
        <p>Schedule your visit with our specialists</p>
    </div>

    <div class="container" style="margin-top: 30px; margin-bottom: 50px;">
        <div class="row">
            <div class="col s12 m8 offset-m2">
                <div class="card">
                    <div class="card-content">
                        <?php if ($selected_doctor): ?>
                            <div class="center" style="margin-bottom: 20px; padding: 15px; background: #f4f7f6; border-radius: 10px;">
                                <h5 style="color: #4a6a5c; margin: 0;">Booking for: <?php echo htmlspecialchars($selected_doctor['name']); ?></h5>
                                <p style="color: #666;"><?php echo htmlspecialchars($selected_doctor['specialization']); ?> | Fee: ৳<?php echo number_format($selected_doctor['fee']); ?> BDT</p>
                                <p style="color: #666; font-size: 0.85rem;">Available: <?php echo $selected_doctor['available_start']; ?> - <?php echo $selected_doctor['available_end']; ?></p>
                            </div>
                        <?php endif; ?>
                        <form id="appointment_form">
                            <div class="row">
                                <div class="input-field col s12 m6">
                                    <input type="text" id="patient_name" class="validate" required>
                                    <label for="patient_name">Patient Name</label>
                                </div>
                                <div class="input-field col s12 m6">
                                    <input type="email" id="patient_email" class="validate" required>
                                    <label for="patient_email">Patient Email</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-field col s12 m6">
                                    <select id="dept_select" required>
                                        <option value="" disabled selected>Choose Department</option>
                                        <?php foreach ($departments as $dept): ?>
                                            <option value="<?php echo $dept['id']; ?>"><?php echo htmlspecialchars($dept['dept_name']); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <label>Department</label>
                                </div>
                                <div class="input-field col s12 m6">
                                    <select id="doctor_select" required>
                                        <option value="" disabled selected>Choose Doctor</option>
                                        <?php if ($selected_doctor): ?>
                                            <option value="<?php echo $selected_doctor['id']; ?>" selected><?php echo htmlspecialchars($selected_doctor['name']); ?></option>
                                        <?php endif; ?>
                                    </select>
                                    <label>Doctor</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-field col s12 m6">
                                    <input type="date" id="app_date" class="validate" required>
                                    <label for="app_date" class="active">Appointment Date</label>
                                </div>
                                <div class="input-field col s12 m6">
                                    <input type="time" id="app_time" class="validate" required>
                                    <label for="app_time" class="active">Appointment Time</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-field col s12">
                                    <textarea id="app_notes" class="materialize-textarea"></textarea>
                                    <label for="app_notes">Additional Notes</label>
                                </div>
                            </div>
                            <div class="center">
                                <button type="submit" class="waves-effect waves-light btn" style="background: #6b9080 !important; padding: 0 40px;">Confirm Appointment</button>
                            </div>
                        </form>
                        <div id="app_msg" class="center" style="margin-top: 15px; font-weight: 500;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php require('chunks/footer.php'); ?>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <script src="js/loaders.js"></script>
    <script>
        $(document).ready(function(){
            $('select').formSelect();
            $('#dept_select').change(function(){
                var dept_id = $(this).val();
                $.ajax({
                    url: 'api/data.php',
                    type: 'POST',
                     {action: 'get_doctors', dept_id: dept_id},
                    success: function(response){
                        var doctors = JSON.parse(response);
                        var options = '<option value="" disabled selected>Choose Doctor</option>';
                        doctors.forEach(function(doc){
                            options += '<option value="'+doc.id+'">'+doc.name+'</option>';
                        });
                        $('#doctor_select').html(options);
                        $('select').formSelect();
                    }
                });
            });
            $('#appointment_form').submit(function(e){
                e.preventDefault();
                var data = {
                    action: 'book_appointment',
                    patient_name: $('#patient_name').val(),
                    patient_email: $('#patient_email').val(),
                    doctor_id: $('#doctor_select').val(),
                    dept_id: $('#dept_select').val(),
                    appointment_date: $('#app_date').val(),
                    appointment_time: $('#app_time').val(),
                    notes: $('#app_notes').val()
                };
                $.ajax({
                    url: 'backends/book-appointment.php',
                    type: 'POST',
                     data,
                    success: function(response){
                        var res = JSON.parse(response);
                        if(res.code == "1"){
                            $('#app_msg').html('<span style="color: #4a6a5c;">'+res.msg+'</span>');
                            $('#appointment_form')[0].reset();
                            $('select').formSelect();
                        } else {
                            $('#app_msg').html('<span style="color: red;">'+res.msg+'</span>');
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>
