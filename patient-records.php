<?php
session_start();
if (!isset($_SESSION['user']) || (isset($_SESSION['role']) && $_SESSION['role'] === 'doctor')) {
    header('location: index.php');
    exit();
}
require_once('backends/connection-pdo.php');
if (!isset($_SESSION['user_email'])) {
    header('location: logout.php');
    exit();
}
$user_email = $_SESSION['user_email'];

// Fetch Prescriptions
$sql_presc = "SELECT p.*, d.name as doctor_name, dept.dept_name FROM prescriptions p JOIN doctors d ON p.doctor_id = d.id JOIN departments dept ON d.dept_id = dept.id WHERE p.patient_email = ? ORDER BY p.id DESC";
$query_presc = $pdoconn->prepare($sql_presc);
$query_presc->execute([$user_email]);
$prescriptions = $query_presc->fetchAll(PDO::FETCH_ASSOC);

// Fetch Appointments
$sql_app = "SELECT a.*, d.name as doctor_name, d.fee as doctor_fee, dept.dept_name FROM appointments a JOIN doctors d ON a.doctor_id = d.id JOIN departments dept ON a.dept_id = dept.id WHERE a.patient_email = ? ORDER BY a.id DESC";
$query_app = $pdoconn->prepare($sql_app);
$query_app->execute([$user_email]);
$appointments = $query_app->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Medical Records | HealthCare MS</title>
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
        <h4>Medical Records</h4>
        <p>Your complete health history in one place</p>
    </div>

    <div class="container" style="margin-top: 30px; margin-bottom: 50px;">
        
        <div class="row">
            <div class="col s12">
                <ul class="tabs">
                    <li class="tab col s6"><a class="active" href="#prescriptions" style="color: #6b9080;">Prescriptions</a></li>
                    <li class="tab col s6"><a href="#billing" style="color: #6b9080;">Billing & History</a></li>
                </ul>
            </div>
            
            <!-- Prescriptions Tab -->
            <div id="prescriptions" class="col s12" style="margin-top: 20px;">
                <div class="card">
                    <div class="card-content">
                        <h5 style="color: #4a6a5c; margin-bottom: 20px;">My Prescriptions</h5>
                        <table class="highlight responsive-table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Doctor</th>
                                    <th>Department</th>
                                    <th>Medications</th>
                                    <th>Instructions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($prescriptions) > 0): ?>
                                    <?php foreach ($prescriptions as $presc): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars(date('d M Y', strtotime($presc['created_at']))); ?></td>
                                            <td><?php echo htmlspecialchars($presc['doctor_name']); ?></td>
                                            <td><?php echo htmlspecialchars($presc['dept_name']); ?></td>
                                            <td><?php echo htmlspecialchars($presc['medications']); ?></td>
                                            <td><?php echo htmlspecialchars($presc['instructions']); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="center">No prescriptions found.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Billing & History Tab -->
            <div id="billing" class="col s12" style="margin-top: 20px;">
                <div class="card">
                    <div class="card-content">
                        <h5 style="color: #4a6a5c; margin-bottom: 20px;">Consultation History & Bills</h5>
                        <table class="highlight responsive-table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Doctor</th>
                                    <th>Department</th>
                                    <th>Bill Amount</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($appointments) > 0): ?>
                                    <?php foreach ($appointments as $app): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($app['appointment_date']); ?></td>
                                            <td><?php echo htmlspecialchars($app['doctor_name']); ?></td>
                                            <td><?php echo htmlspecialchars($app['dept_name']); ?></td>
                                            <td>৳<?php echo number_format($app['doctor_fee']); ?></td>
                                            <td>
                                                <?php
                                                $status_color = 'grey';
                                                $status_text = ucfirst($app['status']);
                                                if ($app['status'] == 'confirmed') $status_color = 'green';
                                                if ($app['status'] == 'completed') {
                                                    $status_color = 'blue';
                                                    $status_text = 'Paid / Completed';
                                                }
                                                if ($app['status'] == 'cancelled') $status_color = 'red';
                                                ?>
                                                <span class="new badge <?php echo $status_color; ?>" data-badge-caption=""><?php echo $status_text; ?></span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="center">No history found.</td>
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
            $('.tabs').tabs();
        });
    </script>
</body>
</html>
