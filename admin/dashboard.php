<?php require('layout/header.php'); ?>
<?php require('layout/left-sidebar-long.php'); ?>
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
?>

<?php
if (isset($_SESSION['msg'])) {
    echo '<div class="section white-text admin-theme-bg" style="padding: 15px; font-weight: 600;">' . $_SESSION['msg'] . '</div>';
    unset($_SESSION['msg']);
}
?>
<div class="section white-text center admin-theme-bg" style="margin-top: 20px; padding: 40px 0; border-radius: 8px;">
    <h4 style="margin: 0; font-weight: 700;">Admin Dashboard</h4>
    <p style="margin-top: 10px; opacity: 0.9;">Overview of System Metrics</p>
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
</div>
<?php require('layout/about-modal.php'); ?>
<?php require('layout/footer.php'); ?>
