<?php require('layout/header.php'); ?>
<?php require('layout/left-sidebar-long.php'); ?>
<?php require('layout/topnav.php'); ?>
<?php require('layout/left-sidebar-short.php'); ?>
<?php
if (isset($_SESSION['msg'])) {
    echo '<div class="section white-text" style="background: #4a6a5c;">' . $_SESSION['msg'] . '</div>';
    unset($_SESSION['msg']);
}
?>
<div class="section white-text center" style="background: #4a6a5c; margin-top: 20px;">
    <h4>Dashboard</h4>
    <div class="row" style="padding: 50px;">
        <div class="col s12">
            <a class="dash-btn" href="appointment-list.php">
                <div class="sec white white-text" style="margin: 15px; padding: 40px; border: 2px solid white; border-radius: 20px; font-size: 20px; background: linear-gradient(to right, #6b9080, #8ca699);">Appointments</div>
            </a>
            <a class="dash-btn" href="doctor-list.php">
                <div class="sec white white-text" style="margin: 15px; padding: 40px; border: 2px solid white; border-radius: 20px; font-size: 20px; background: linear-gradient(to right, #6b9080, #8ca699);">Doctors</div>
            </a>
            <a class="dash-btn" href="department-list.php">
                <div class="sec white white-text" style="margin: 15px; padding: 40px; border: 2px solid white; border-radius: 20px; font-size: 20px; background: linear-gradient(to right, #6b9080, #8ca699);">Departments</div>
            </a>
        </div>
    </div>
</div>
<?php require('layout/about-modal.php'); ?>
<?php require('layout/footer.php'); ?>
