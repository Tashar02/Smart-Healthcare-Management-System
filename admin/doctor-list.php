<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('location: login-admin.php');
    exit();
}
require_once('../backends/connection-pdo.php');
$sql = "SELECT d.*, dept.dept_name FROM doctors d JOIN departments dept ON d.dept_id = dept.id ORDER BY d.id DESC";
$query = $pdoconn->prepare($sql);
$query->execute();
$doctors = $query->fetchAll(PDO::FETCH_ASSOC);
?>
<?php require('layout/header.php'); ?>
<?php require('layout/left-sidebar-long.php'); ?>
<?php require('layout/topnav.php'); ?>
<?php require('layout/left-sidebar-short.php'); ?>
<?php
if (isset($_SESSION['msg'])) {
    echo '<div class="section white-text" style="background: #4a6a5c;">'.$_SESSION['msg'].'</div>';
    unset($_SESSION['msg']);
}
?>
<div class="section white-text center" style="background: #4a6a5c; margin-top: 20px;">
    <h4>Doctors</h4>
</div>
<div class="container" style="margin-top: 30px; margin-bottom: 50px;">
    <div class="row">
        <div class="col s12">
            <div class="section right" style="margin-bottom: 15px;">
                <a href="doctor-add.php" class="waves-effect waves-light btn" style="background: #6b9080 !important;">Add Doctor</a>
            </div>
            <div class="card">
                <div class="card-content">
                    <table class="highlight responsive-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Department</th>
                                <th>Specialization</th>
                                <th>Fee</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($doctors) > 0): ?>
                                <?php foreach ($doctors as $doc): ?>
                                    <tr>
                                        <td><?php echo $doc['id']; ?></td>
                                        <td><?php echo htmlspecialchars($doc['name']); ?></td>
                                        <td><?php echo htmlspecialchars($doc['dept_name']); ?></td>
                                        <td><?php echo htmlspecialchars($doc['specialization']); ?></td>
                                        <td>৳<?php echo number_format($doc['fee']); ?> BDT</td>
                                        <td>
                                            <a href="../backends/delete-record.php?type=doctor&id=<?php echo $doc['id']; ?>" class="btn-small red darken-1">Delete</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="center">No doctors found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require('layout/about-modal.php'); ?>
<?php require('layout/footer.php'); ?>
