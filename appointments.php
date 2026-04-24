<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('location: index.php');
    exit();
}
if (isset($_SESSION['role']) && $_SESSION['role'] == 'doctor') {
    header('location: index.php');
    exit();
}
require_once('backends/connection-pdo.php');
$user_email = $_SESSION['user_email'];

// Fetch user's full name
$sql_user = "SELECT name FROM users WHERE email = ?";
$query_user = $pdoconn->prepare($sql_user);
$query_user->execute([$user_email]);
$user_data = $query_user->fetch(PDO::FETCH_ASSOC);
$full_name = $user_data ? $user_data['name'] : $_SESSION['user'];

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
                                    <input type="text" id="patient_name" value="<?php echo htmlspecialchars($full_name); ?>" readonly required>
                                    <label for="patient_name" class="active">Patient Name</label>
                                </div>
                                <div class="input-field col s12 m6">
                                    <input type="email" id="patient_email" value="<?php echo htmlspecialchars($user_email); ?>" readonly required>
                                    <label for="patient_email" class="active">Patient Email</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-field col s12 m6">
                                    <select id="dept_select" required>
                                        <option value="" disabled <?php echo !$selected_doctor ? 'selected' : ''; ?>>Choose Department</option>
                                        <?php foreach ($departments as $dept): ?>
                                            <option value="<?php echo $dept['id']; ?>" <?php echo ($selected_doctor && $selected_doctor['dept_id'] == $dept['id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($dept['dept_name']); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <label>Department</label>
                                </div>
                                <div class="input-field col s12 m6">
                                    <select id="doctor_select" required>
                                        <option value="" disabled <?php echo !$selected_doctor ? 'selected' : ''; ?>>Choose Doctor</option>
                                        <?php if ($selected_doctor): ?>
                                            <option value="<?php echo $selected_doctor['id']; ?>" data-fee="<?php echo $selected_doctor['fee']; ?>" data-start="<?php echo $selected_doctor['available_start']; ?>" data-end="<?php echo $selected_doctor['available_end']; ?>" selected><?php echo htmlspecialchars($selected_doctor['name']); ?></option>
                                        <?php endif; ?>
                                    </select>
                                    <label>Doctor</label>
                                </div>
                            </div>
                            <div class="row" id="fee_display_row" style="display: <?php echo $selected_doctor ? 'block' : 'none'; ?>;">
                                <div class="col s12 center">
                                    <h5 style="color: #4a6a5c;">Total Bill: ৳<span id="display_fee"><?php echo $selected_doctor ? number_format($selected_doctor['fee']) : '0'; ?></span> BDT</h5>
                                    <p class="grey-text">Please note this amount to be paid at the counter.</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-field col s12 m6">
                                    <input type="date" id="app_date" class="validate" required>
                                    <label for="app_date" class="active">Appointment Date</label>
                                </div>
                                <div class="input-field col s12 m6">
                                    <select id="app_time" required>
                                        <option value="" disabled selected>Select Time Slot</option>
                                    </select>
                                    <label>Appointment Time</label>
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
                    data: {action: 'get_doctors', dept_id: dept_id},
                    success: function(response){
                        var doctors = JSON.parse(response);
                        var options = '<option value="" disabled selected>Choose Doctor</option>';
                        doctors.forEach(function(doc){
                            options += '<option value="'+doc.id+'" data-fee="'+doc.fee+'" data-start="'+doc.available_start+'" data-end="'+doc.available_end+'">'+doc.name+'</option>';
                        });
                        $('#doctor_select').html(options);
                        $('select').formSelect();
                        $('#fee_display_row').hide();
                        $('#app_time').html('<option value="" disabled selected>Select Time Slot</option>');
                        $('select').formSelect();
                    }
                });
            });
            function generateTimeSlots(start, end) {
                var slots = '<option value="" disabled selected>Select Time Slot</option>';
                var startTime = start.split(':');
                var endTime = end.split(':');
                var startHour = parseInt(startTime[0]);
                var startMin = parseInt(startTime[1]);
                var endHour = parseInt(endTime[0]);
                var endMin = parseInt(endTime[1]);

                var current = new Date();
                current.setHours(startHour, startMin, 0, 0);
                
                var targetEnd = new Date();
                targetEnd.setHours(endHour, endMin, 0, 0);

                while (current < targetEnd) {
                    var h = current.getHours();
                    var m = current.getMinutes();
                    var timeStr = (h < 10 ? '0' + h : h) + ':' + (m < 10 ? '0' + m : m);
                    slots += '<option value="' + timeStr + '">' + timeStr + '</option>';
                    current.setMinutes(current.getMinutes() + 20);
                }
                $('#app_time').html(slots);
                $('select').formSelect();
            }
            $('#doctor_select').change(function(){
                var selected = $(this).find(':selected');
                var fee = selected.data('fee');
                var start = selected.data('start');
                var end = selected.data('end');
                if(fee) {
                    $('#display_fee').text(fee);
                    $('#fee_display_row').show();
                    generateTimeSlots(start, end);
                } else {
                    $('#fee_display_row').hide();
                    $('#app_time').html('<option value="" disabled selected>Select Time Slot</option>');
                    $('select').formSelect();
                }
            });
            <?php if($selected_doctor): ?>
            generateTimeSlots('<?php echo $selected_doctor['available_start']; ?>', '<?php echo $selected_doctor['available_end']; ?>');
            <?php endif; ?>
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
                    data: data,
                    success: function(response){
                        var res = JSON.parse(response);
                        if(res.code == "1"){
                            $('#app_msg').html('<span style="color: #4a6a5c;">'+res.msg+'</span>');
                            $('#appointment_form')[0].reset();
                            // Re-populate readonly fields
                            $('#patient_name').val("<?php echo addslashes($full_name); ?>");
                            $('#patient_email').val("<?php echo addslashes($user_email); ?>");
                            $('label[for="patient_name"], label[for="patient_email"]').addClass('active');
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
