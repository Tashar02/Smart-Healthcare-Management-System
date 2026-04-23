<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('location: login-admin.php');
    exit();
}
require_once('../backends/connection-pdo.php');
$sql = "SELECT * FROM departments";
$query = $pdoconn->prepare($sql);
$query->execute();
$departments = $query->fetchAll(PDO::FETCH_ASSOC);
?>
<?php require('layout/header.php'); ?>
<?php require('layout/left-sidebar-long.php'); ?>
<?php require('layout/topnav.php'); ?>
<?php require('layout/left-sidebar-short.php'); ?>
<div class="section white-text center" style="background: #4a6a5c; margin-top: 20px;">
    <h4>Add Doctor</h4>
</div>
<div class="container" style="margin-top: 30px; margin-bottom: 50px;">
    <div class="row">
        <div class="col s12 m8 offset-m2">
            <div class="card">
                <div class="card-content">
                    <form action="../backends/doctor-add.php" method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <div class="input-field col s12">
                                <input type="text" id="name" name="name" class="validate" required>
                                <label for="name">Doctor Name</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <select id="dept_id" name="dept_id" required>
                                    <option value="" disabled selected>Choose Department</option>
                                    <?php foreach ($departments as $dept): ?>
                                        <option value="<?php echo $dept['id']; ?>"><?php echo htmlspecialchars($dept['dept_name']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <label>Department</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <input type="text" id="specialization" name="specialization" class="validate" required>
                                <label for="specialization">Specialization</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <input type="number" id="fee" name="fee" class="validate" required>
                                <label for="fee">Consultation Fee</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="file-field input-field col s12">
                                <div class="btn" style="background: #6b9080 !important;">
                                    <span>Image</span>
                                    <input type="file" name="image" required>
                                </div>
                                <div class="file-path-wrapper">
                                    <input class="file-path validate" type="text" placeholder="Upload doctor photo">
                                </div>
                            </div>
                        </div>
                        <div class="center">
                            <button type="submit" class="waves-effect waves-light btn" style="background: #6b9080 !important; padding: 0 40px;">Save Doctor</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require('layout/about-modal.php'); ?>
<?php require('layout/footer.php'); ?>
