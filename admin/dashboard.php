<?php require('layout/header.php'); ?>
<?php require('layout/topnav.php'); ?>
<?php require('layout/left-sidebar-short.php'); ?>
<?php require_once('../backends/connection-pdo.php'); ?>

<?php
// Fetch metrics
$doc_query = $pdoconn->query("SELECT COUNT(*) FROM doctors");
$total_doctors = $doc_query->fetchColumn();

$app_query = $pdoconn->query("SELECT COUNT(*) FROM appointments");
$total_appointments = $app_query->fetchColumn();

$dept_query = $pdoconn->query("SELECT COUNT(*) FROM departments");
$total_departments = $dept_query->fetchColumn();

$user_query = $pdoconn->query("SELECT COUNT(*) FROM users");
$total_users = $user_query->fetchColumn();

$revenue_query = $pdoconn->query("SELECT SUM(amount) FROM billings WHERE status = 'completed'");
$total_revenue = $revenue_query->fetchColumn() ?: 0;

$pending_query = $pdoconn->query("SELECT SUM(amount) FROM billings WHERE status = 'pending'");
$total_pending = $pending_query->fetchColumn() ?: 0;
?>

<?php
if (isset($_SESSION['msg'])) {
    echo '<div class="container" style="margin-top: 20px;"><div class="admin-alert">'.$_SESSION['msg'].'</div></div>';
    unset($_SESSION['msg']);
}
?>
<div class="section admin-theme-bg center" style="margin-top: 20px;">
    <h4>Admin Dashboard</h4>
</div>

<div class="container" style="margin-top: 40px; margin-bottom: 50px;">
    <div class="row">
        <!-- Appointments Card -->
        <div class="col s12 m6 l3">
            <a href="appointment-list.php">
                <div class="metric-card admin-gradient center">
                    <i class="material-icons">event_note</i>
                    <h3><?php echo $total_appointments; ?></h3>
                    <p>Appointments</p>
                </div>
            </a>
        </div>
        
        <!-- Doctors Card -->
        <div class="col s12 m6 l3">
            <a href="doctor-list.php">
                <div class="metric-card admin-gradient center">
                    <i class="material-icons">local_hospital</i>
                    <h3><?php echo $total_doctors; ?></h3>
                    <p>Doctors</p>
                </div>
            </a>
        </div>

        <!-- Departments Card -->
        <div class="col s12 m6 l3">
            <a href="department-list.php">
                <div class="metric-card admin-gradient center">
                    <i class="material-icons">business</i>
                    <h3><?php echo $total_departments; ?></h3>
                    <p>Departments</p>
                </div>
            </a>
        </div>

        <!-- Users Card -->
        <div class="col s12 m6 l3">
            <a href="user-list.php">
                <div class="metric-card admin-gradient center">
                    <i class="material-icons">people</i>
                    <h3><?php echo $total_users; ?></h3>
                    <p>Users</p>
                </div>
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Revenue Card -->
        <div class="col s12 m6">
            <a href="billing-list.php">
                <div class="metric-card center" style="background: linear-gradient(135deg, #2e7d32, #4caf50) !important; box-shadow: 0 4px 20px rgba(46, 125, 50, 0.2);">
                    <i class="material-icons">account_balance_wallet</i>
                    <h3>৳<?php echo number_format($total_revenue); ?></h3>
                    <p>Total Revenue (Paid)</p>
                </div>
            </a>
        </div>

        <!-- Pending Collection Card -->
        <div class="col s12 m6">
            <a href="billing-list.php">
                <div class="metric-card center" style="background: linear-gradient(135deg, #d84315, #ff5722) !important; box-shadow: 0 4px 20px rgba(216, 67, 21, 0.2);">
                    <i class="material-icons">payment</i>
                    <h3>৳<?php echo number_format($total_pending); ?></h3>
                    <p>Pending Collections</p>
                </div>
            </a>
        </div>
    </div>
</div>
<?php require('layout/about-modal.php'); ?>
<?php require('layout/footer.php'); ?>
