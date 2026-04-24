<?php
session_start();
require_once('backends/connection-pdo.php');
$sql = "SELECT * FROM departments";
$query = $pdoconn->prepare($sql);
$query->execute();
$departments = $query->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Departments | HealthCare MS</title>
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
        <h4>Medical Departments</h4>
        <p>Explore our specialized healthcare departments</p>
    </div>

    <div class="container" style="margin-top: 30px; margin-bottom: 50px;">
        <div class="row">
            <?php if (count($departments) > 0): ?>
                <?php foreach ($departments as $dept): ?>
                    <div class="col s12 m6 l4">
                        <div class="card hoverable">
                            <div class="card-content">
                                <span class="card-title" style="color: #4a6a5c; font-weight: 600;"><?php echo htmlspecialchars($dept['dept_name']); ?></span>
                                <p style="color: #666;"><?php echo htmlspecialchars($dept['short_desc']); ?></p>
                            </div>
                            <div class="card-action">
                                <a href="doctors.php?dept_id=<?php echo $dept['id']; ?>" style="color: #6b9080; font-weight: 500;">View Doctors</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col s12 center">
                    <h5 style="color: #999;">No departments available yet.</h5>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php require('chunks/footer.php'); ?>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <script src="js/loaders.js"></script>
    <script src="js/ajax.js"></script>
</body>
</html>
