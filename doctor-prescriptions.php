<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'doctor') {
    header('location: index.php');
    exit();
}
require_once('backends/connection-pdo.php');
$doctor_id = $_SESSION['user_id'];
$sql = "SELECT p.*, u.name as patient_name FROM prescriptions p JOIN users u ON p.patient_email = u.email WHERE p.doctor_id = ? ORDER BY p.id DESC";
$query = $pdoconn->prepare($sql);
$query->execute([$doctor_id]);
$prescriptions = $query->fetchAll(PDO::FETCH_ASSOC);
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

    <?php require('chunks/footer.php'); ?>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <script src="js/loaders.js"></script>
    <script>
        $(document).ready(function(){
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
        });
    </script>
</body>
</html>
