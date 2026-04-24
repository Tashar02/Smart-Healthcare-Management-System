<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('location: login-admin.php');
    exit();
}
require_once('../backends/connection-pdo.php');
$sql = "SELECT * FROM users ORDER BY id DESC";
$query = $pdoconn->prepare($sql);
$query->execute();
$users = $query->fetchAll(PDO::FETCH_ASSOC);
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
    <h4 style="margin: 0; font-weight: 700;">Registered Users</h4>
</div>
<div class="container" style="margin-top: 30px; margin-bottom: 50px;">
    <div class="row">
        <div class="col s12">
            <div class="card">
                <div class="card-content">
                    <table class="striped highlight responsive-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Registered On</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($users) > 0): ?>
                                <?php foreach ($users as $user): ?>
                                    <tr>
                                        <td><?php echo $user['id']; ?></td>
                                        <td><?php echo htmlspecialchars($user['name']); ?></td>
                                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                                        <td><span class="new badge blue" data-badge-caption=""><?php echo ucfirst($user['role']); ?></span></td>
                                        <td><?php echo htmlspecialchars($user['timestamp']); ?></td>
                                        <td>
                                            <a href="../backends/delete-record.php?type=user&id=<?php echo $user['id']; ?>" class="btn-small red darken-1">Delete</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="center">No users found.</td>
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
