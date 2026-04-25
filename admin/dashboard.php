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
                <div class="metric-card admin-gradient center-align">
                    <i class="material-icons">event_note</i>
                    <h3><?php echo $total_appointments; ?></h3>
                    <p>Appointments</p>
                </div>
            </a>
        </div>
        
        <!-- Doctors Card -->
        <div class="col s12 m6 l3">
            <a href="doctor-list.php">
                <div class="metric-card admin-gradient center-align">
                    <i class="material-icons">local_hospital</i>
                    <h3><?php echo $total_doctors; ?></h3>
                    <p>Doctors</p>
                </div>
            </a>
        </div>

        <!-- Departments Card -->
        <div class="col s12 m6 l3">
            <a href="department-list.php">
                <div class="metric-card admin-gradient center-align">
                    <i class="material-icons">business</i>
                    <h3><?php echo $total_departments; ?></h3>
                    <p>Departments</p>
                </div>
            </a>
        </div>

        <!-- Users Card -->
        <div class="col s12 m6 l3">
            <a href="user-list.php">
                <div class="metric-card admin-gradient center-align">
                    <i class="material-icons">people</i>
                    <h3><?php echo $total_users; ?></h3>
                    <p>Users</p>
                </div>
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Unified Billing Card -->
        <div class="col s12">
            <a href="billing-list.php">
                <div class="metric-card center-align" style="background: linear-gradient(135deg, #4e342e, #6d4c41) !important; box-shadow: 0 4px 20px rgba(78, 52, 46, 0.2); padding: 30px; border: 2px solid #ef6c00;">
                    <i class="material-icons" style="font-size: 3.5rem; color: #ff9800;">payments</i>
                    <h3 style="font-size: 2rem; margin-top: 15px; color: #fff;">
                        Paid: <span style="color: #81c784;">৳<?php echo number_format($total_revenue); ?></span> | 
                        Pending: <span style="color: #ffb74d;">৳<?php echo number_format($total_pending); ?></span>
                    </h3>
                    <p style="font-size: 1.1rem; color: #d7ccc8; font-weight: 500;">Financial Overview & Billings</p>
                </div>
            </a>
        </div>
    </div>
</div>
<?php require('layout/about-modal.php'); ?>
<?php require('layout/footer.php'); ?>
