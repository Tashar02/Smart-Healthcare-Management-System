<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'doctor' || !isset($_SESSION['doctor_id'])) {
    header('location: index.php');
    exit();
}
require_once('backends/connection-pdo.php');
$doctor_id = isset($_SESSION['doctor_id']) ? $_SESSION['doctor_id'] : 0;
$sql = "SELECT p.*, u.name as patient_name FROM prescriptions p JOIN users u ON p.patient_email = u.email WHERE p.doctor_id = ? ORDER BY p.id DESC";
$query = $pdoconn->prepare($sql);
$query->execute([$doctor_id]);
$prescriptions = $query->fetchAll(PDO::FETCH_ASSOC);

date_default_timezone_set("Asia/Dhaka");
$today = date('Y-m-d');
$sql_today = "SELECT * FROM appointments WHERE doctor_id = ? AND appointment_date = ? AND status NOT IN ('completed', 'cancelled') ORDER BY appointment_time ASC";
$query_today = $pdoconn->prepare($sql_today);
$query_today->execute([$doctor_id, $today]);
$today_appointments = $query_today->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Prescriptions | HealthCare MS</title>
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
        <h4>Prescription Management</h4>
        <p>View and issue prescriptions for your patients</p>
    </div>

    <div class="container" style="margin-top: 30px; margin-bottom: 50px;">
        <div class="row">
            <div class="col s12 m8 offset-m2">
                <div class="card">
                    <div class="card-content">
                        <h5 style="color: #4a6a5c; margin-bottom: 20px;">Issue New Prescription</h5>
                        <form id="prescription_form">
                            <div class="row">
                                <div class="input-field col s12">
                                    <input type="email" id="patient_email" class="validate" required>
                                    <label for="patient_email">Patient Email</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-field col s12">
                                    <input type="text" id="medications" class="validate" required>
                                    <label for="medications">Medications & Dosage</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-field col s12">
                                    <textarea id="instructions" class="materialize-textarea"></textarea>
                                    <label for="instructions">Special Instructions</label>
                                </div>
                            </div>
                            <div class="center">
                                <button type="submit" class="waves-effect waves-light btn" style="background: #6b9080 !important; padding: 0 40px;">Save Prescription</button>
                            </div>
                        </form>
                        <div id="presc_msg" class="center" style="margin-top: 15px; font-weight: 500;"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col s12">
                <div class="card">
                    <div class="card-content">
                        <h5 style="color: #4a6a5c; margin-bottom: 20px;">Today's Pending Patients</h5>
                        <?php if (count($today_appointments) > 0): ?>
                            <table class="highlight responsive-table">
                                <thead>
                                    <tr>
                                        <th>Time</th>
                                        <th>Patient Name</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($today_appointments as $app): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($app['appointment_time']); ?></td>
                                            <td>
                                                <a href="#!" class="patient-history-trigger" 
                                                   data-email="<?php echo htmlspecialchars($app['patient_email']); ?>" 
                                                   data-name="<?php echo htmlspecialchars($app['patient_name']); ?>"
                                                   data-notes="<?php echo htmlspecialchars($app['notes']); ?>"
                                                   style="color: #6b9080; font-weight: 600; text-decoration: underline;">
                                                    <?php echo htmlspecialchars($app['patient_name']); ?>
                                                </a>
                                            </td>
                                            <td>
                                                <button class="btn-small waves-effect waves-light fill-email-btn" 
                                                        data-email="<?php echo htmlspecialchars($app['patient_email']); ?>"
                                                        style="background: #6b9080 !important;">
                                                    Prescribe
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <p class="center grey-text">No pending appointments for today.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col s12">
                <div class="card">
                    <div class="card-content">
                        <h5 style="color: #4a6a5c; margin-bottom: 20px;">Issued Prescriptions</h5>
                        <table class="highlight responsive-table">
                            <thead>
                                <tr>
                                    <th>Patient</th>
                                    <th>Medications</th>
                                    <th>Date</th>
                                    <th>Instructions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($prescriptions) > 0): ?>
                                    <?php foreach ($prescriptions as $presc): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($presc['patient_name']); ?></td>
                                            <td><?php echo htmlspecialchars($presc['medications']); ?></td>
                                            <td><?php echo htmlspecialchars($presc['created_at']); ?></td>
                                            <td><?php echo htmlspecialchars($presc['instructions']); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="center">No prescriptions issued yet.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Patient History -->
    <div id="historyModal" class="modal modal-fixed-footer" style="width: 80% !important; max-height: 80% !important;">
        <div class="modal-content">
            <h4 id="modalPatientName" style="color: #4a6a5c;">Patient History</h4>
            <div id="patientNotesSection" style="margin-bottom: 25px; padding: 15px; background: #f4f7f6; border-radius: 10px; border-left: 5px solid #6b9080;">
                <strong style="color: #4a6a5c;">Current Appointment Notes:</strong>
                <p id="modalPatientNotes" style="margin-top: 5px; color: #555;"></p>
            </div>
            <h5 style="color: #4a6a5c; margin-top: 30px; border-bottom: 2px solid #f4f7f6; padding-bottom: 10px;">Previous Prescriptions</h5>
            <div id="historyContent" style="margin-top: 15px;">
                <div class="center" style="padding: 40px;">
                    <div class="preloader-wrapper small active">
                        <div class="spinner-layer spinner-green-only">
                            <div class="circle-clipper left"><div class="circle"></div></div>
                            <div class="gap-patch"><div class="circle"></div></div>
                            <div class="circle-clipper right"><div class="circle"></div></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <a href="#!" class="modal-close waves-effect waves-green btn-flat">Close History</a>
        </div>
    </div>

    <?php require('chunks/footer.php'); ?>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <script src="js/loaders.js"></script>
    <script>
        $(document).ready(function(){
            $('.modal').modal();
            
            $('#prescription_form').submit(function(e){
                e.preventDefault();
                var data = {
                    action: 'add_prescription',
                    patient_email: $('#patient_email').val(),
                    medications: $('#medications').val(),
                    instructions: $('#instructions').val()
                };
                $.ajax({
                    url: 'backends/prescription-add.php',
                    type: 'POST',
                    data: data,
                    success: function(response){
                        var res = JSON.parse(response);
                        if(res.code == "1"){
                            $('#presc_msg').html('<span style="color: #4a6a5c;">'+res.msg+'</span>');
                            $('#prescription_form')[0].reset();
                            setTimeout(function(){ location.reload(); }, 1500);
                        } else {
                            $('#presc_msg').html('<span style="color: red;">'+res.msg+'</span>');
                        }
                    }
                });
            });

            $('.patient-history-trigger').click(function(){
                var email = $(this).data('email');
                var name = $(this).data('name');
                var notes = $(this).data('notes');
                
                $('#modalPatientName').text(name + "'s History");
                $('#modalPatientNotes').text(notes ? notes : 'No notes provided.');
                $('#historyContent').html('<div class="center" style="padding: 40px;"><div class="preloader-wrapper small active"><div class="spinner-layer spinner-green-only"><div class="circle-clipper left"><div class="circle"></div></div><div class="gap-patch"><div class="circle"></div></div><div class="circle-clipper right"><div class="circle"></div></div></div></div></div>');
                $('#historyModal').modal('open');
                
                $.ajax({
                    url: 'api/data.php',
                    type: 'POST',
                    data: {action: 'get_patient_history', email: email},
                    success: function(response){
                        var history = JSON.parse(response);
                        if(history.length > 0){
                            var html = '<table class="striped responsive-table"><thead><tr><th>Date</th><th>Doctor</th><th>Patient Notes</th><th>Medications</th><th>Instructions</th></tr></thead><tbody>';
                            history.forEach(function(item){
                                html += '<tr><td>'+item.appointment_date+'</td><td>'+item.doctor_name+'</td><td>'+(item.notes ? item.notes : '-')+'</td><td>'+(item.medications ? item.medications : '-')+'</td><td>'+(item.instructions ? item.instructions : '-')+'</td></tr>';
                            });
                            html += '</tbody></table>';
                            $('#historyContent').html(html);
                        } else {
                            $('#historyContent').html('<p class="center grey-text">No previous prescription history found for this patient.</p>');
                        }
                    }
                });
            });

            $('.fill-email-btn').click(function(){
                var email = $(this).data('email');
                $('#patient_email').val(email);
                M.updateTextFields();
                $('html, body').animate({
                    scrollTop: $("#prescription_form").offset().top - 100
                }, 500);
            });
        });
    </script>
</body>
</html>
