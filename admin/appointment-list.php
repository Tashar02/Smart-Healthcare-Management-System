<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('location: login-admin.php');
    exit();
}
require_once('../backends/connection-pdo.php');
$sql = "SELECT a.*, d.name as doctor_name, dept.dept_name FROM appointments a JOIN doctors d ON a.doctor_id = d.id JOIN departments dept ON a.dept_id = dept.id ORDER BY a.id DESC";
$query = $pdoconn->prepare($sql);
$query->execute();
$appointments = $query->fetchAll(PDO::FETCH_ASSOC);
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
    <h4 style="margin: 0; font-weight: 700;">Appointments</h4>
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
                                <th>Patient</th>
                                <th>Doctor</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($appointments) > 0): ?>
                                <?php foreach ($appointments as $app): ?>
                                    <tr>
                                        <td><?php echo $app['id']; ?></td>
                                        <td><?php echo htmlspecialchars($app['patient_name']); ?></td>
                                        <td><?php echo htmlspecialchars($app['doctor_name']); ?></td>
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
                                        <td>
                                            <form action="../backends/update-appointment.php" method="POST" style="display: inline;">
                                                <input type="hidden" name="id" value="<?php echo $app['id']; ?>">
                                                <select name="status" onchange="this.form.submit()" style="display: inline-block; width: auto; height: 30px;">
                                                    <option value="pending" <?php echo ($app['status'] == 'pending') ? 'selected' : ''; ?>>Pending</option>
                                                    <option value="confirmed" <?php echo ($app['status'] == 'confirmed') ? 'selected' : ''; ?>>Confirmed</option>
                                                    <option value="completed" <?php echo ($app['status'] == 'completed') ? 'selected' : ''; ?>>Completed</option>
                                                    <option value="cancelled" <?php echo ($app['status'] == 'cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                                                </select>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="center">No appointments found.</td>
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
