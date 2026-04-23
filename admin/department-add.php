<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('location: login-admin.php');
    exit();
}
?>
<?php require('layout/header.php'); ?>
<?php require('layout/left-sidebar-long.php'); ?>
<?php require('layout/topnav.php'); ?>
<?php require('layout/left-sidebar-short.php'); ?>
<div class="section white-text center" style="background: #4a6a5c; margin-top: 20px;">
    <h4>Add Department</h4>
</div>
<div class="container" style="margin-top: 30px; margin-bottom: 50px;">
    <div class="row">
        <div class="col s12 m8 offset-m2">
            <div class="card">
                <div class="card-content">
                    <form action="../backends/department-add.php" method="POST">
                        <div class="row">
                            <div class="input-field col s12">
                                <input type="text" id="dept_name" name="dept_name" class="validate" required>
                                <label for="dept_name">Department Name</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <input type="text" id="short_desc" name="short_desc" class="validate" required>
                                <label for="short_desc">Short Description</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <textarea id="long_desc" name="long_desc" class="materialize-textarea" required></textarea>
                                <label for="long_desc">Long Description</label>
                            </div>
                        </div>
                        <div class="center">
                            <button type="submit" class="waves-effect waves-light btn" style="background: #6b9080 !important; padding: 0 40px;">Save Department</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require('layout/about-modal.php'); ?>
<?php require('layout/footer.php'); ?>
