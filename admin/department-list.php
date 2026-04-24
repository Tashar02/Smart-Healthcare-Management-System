<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('location: login-admin.php');
    exit();
}
require_once('../backends/connection-pdo.php');
$sql = "SELECT * FROM departments ORDER BY id DESC";
$query = $pdoconn->prepare($sql);
$query->execute();
$departments = $query->fetchAll(PDO::FETCH_ASSOC);
?>
<?php require('layout/header.php'); ?>
<?php require('layout/left-sidebar-long.php'); ?>
<?php require('layout/topnav.php'); ?>
<?php require('layout/left-sidebar-short.php'); ?>
<?php
if (isset($_SESSION['msg'])) {
    echo '<div class="section white-text admin-theme-bg" style="padding: 15px; font-weight: 600;">'.$_SESSION['msg'].'</div>';
    unset($_SESSION['msg']);
}
?>
<div class="section white-text center admin-theme-bg" style="margin-top: 20px; padding: 20px 0; border-radius: 8px;">
    <h4 style="margin: 0; font-weight: 700;">Departments</h4>
</div>
<div class="container" style="margin-top: 30px; margin-bottom: 50px;">
    <div class="row">
        <div class="col s12">
            <div class="section right" style="margin-bottom: 15px;">
                <a href="department-add.php" class="waves-effect waves-light btn admin-btn"><i class="material-icons left">add_circle</i>Add Department</a>
            </div>
            <div class="card">
                <div class="card-content">
                    <table class="striped highlight responsive-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Department Name</th>
                                <th>Short Description</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($departments) > 0): ?>
                                <?php foreach ($departments as $dept): ?>
                                    <tr>
                                        <td><?php echo $dept['id']; ?></td>
                                        <td><?php echo htmlspecialchars($dept['dept_name']); ?></td>
                                        <td><?php echo htmlspecialchars($dept['short_desc']); ?></td>
                                        <td>
                                            <a href="../backends/delete-record.php?type=department&id=<?php echo $dept['id']; ?>" class="btn-small red darken-1">Delete</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="center">No departments found.</td>
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
