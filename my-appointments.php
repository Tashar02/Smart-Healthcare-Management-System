<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('location: index.php');
    exit();
}
require_once('backends/connection-pdo.php');
$user_email = $_SESSION['user_email'];

$sql = "SELECT a.*, d.name as doctor_name, dept.dept_name FROM appointments a JOIN doctors d ON a.doctor_id = d.id JOIN departments dept ON a.dept_id = dept.id WHERE a.patient_email = ? ORDER BY a.id DESC";
$query = $pdoconn->prepare($sql);
$query->execute([$user_email]);
$appointments = $query->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Appointments | HealthCare MS</title>
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
        <h4>My Appointments</h4>
        <p>View your booking history and status</p>
    </div>

    <div class="container" style="margin-top: 30px; margin-bottom: 50px;">
        <div class="row">
            <div class="col s12">
                <div class="card">
                    <div class="card-content">
                        <table class="highlight responsive-table">
                            <thead>
                                <tr>
                                    <th>Doctor</th>
                                    <th>Department</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($appointments) > 0): ?>
                                    <?php foreach ($appointments as $app): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($app['doctor_name']); ?></td>
                                            <td><?php echo htmlspecialchars($app['dept_name']); ?></td>
                                            <td><?php echo htmlspecialchars($app['appointment_date']); ?></td>
                                            <td><?php echo htmlspecialchars($app['appointment_time']); ?></td>
                                            <td>
                                                <?php
                                                $status_color = 'grey';
                                                if ($app['status'] == 'confirmed') $status_color = 'green';
                                                if ($app['status'] == 'completed') $status_color = 'blue';
                                                if ($app['status'] == 'cancelled') $status_color = 'red';
                                                ?>
                                                <span class="new badge <?php echo $status_color; ?>" data-badge-caption=""><?php echo ucfirst($app['status']); ?></span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="center">No appointments found. Book your first visit today!</td>
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
    <script src="js/ajax.js"></script>
</body>
</html>
