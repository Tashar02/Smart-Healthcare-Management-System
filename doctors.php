<?php
session_start();
require_once('backends/connection-pdo.php');
$dept_id = isset($_GET['dept_id']) ? intval($_GET['dept_id']) : 0;
if ($dept_id > 0) {
    $sql = "SELECT d.*, dept.dept_name FROM doctors d JOIN departments dept ON d.dept_id = dept.id WHERE d.dept_id = ?";
    $query = $pdoconn->prepare($sql);
    $query->execute([$dept_id]);
} else {
    $sql = "SELECT d.*, dept.dept_name FROM doctors d JOIN departments dept ON d.dept_id = dept.id";
    $query = $pdoconn->prepare($sql);
    $query->execute();
}
$doctors = $query->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Doctors | HealthCare MS</title>
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
        <h4>Our Doctors</h4>
        <p>Meet our experienced medical professionals</p>
    </div>

    <div class="container" style="margin-top: 30px; margin-bottom: 50px;">
        <div class="row">
            <?php if (count($doctors) > 0): ?>
                <?php foreach ($doctors as $doc): ?>
                    <div class="col s12 m6 l4">
                        <div class="card hoverable">
                            <div class="card-image">
                                <?php 
                                $img_src = htmlspecialchars($doc['image']);
                                if (empty($img_src) || !file_exists("images/" . $img_src)) {
                                    $img_src = "images/default-doctor.jpg";
                                }
                                ?>
                                <img src="<?php echo $img_src; ?>" style="height: 200px; object-fit: cover;">
                                <span class="card-title"><?php echo htmlspecialchars($doc['name']); ?></span>
                            </div>
                            <div class="card-content">
                                <span class="card-title" style="font-size: 0.95rem; color: #6b9080;"><?php echo htmlspecialchars($doc['specialization']); ?></span>
                                <p style="color: #666; margin-top: 5px;">Department: <?php echo htmlspecialchars($doc['dept_name']); ?></p>
                                <p style="color: #333; font-weight: 600; margin-top: 10px;">Fee: ৳<?php echo number_format($doc['fee']); ?> BDT</p>
                            </div>
                            <div class="card-action">
                                <a href="appointments.php?doctor_id=<?php echo $doc['id']; ?>" class="waves-effect waves-light btn" style="background: #6b9080 !important; color: white !important; width: 100%;">Book Appointment</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col s12 center">
                    <h5 style="color: #999;">No doctors available yet.</h5>
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
