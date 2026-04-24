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
<?php require('layout/topnav.php'); ?>
<?php require('layout/left-sidebar-short.php'); ?>
<?php
if (isset($_SESSION['msg'])) {
    echo '<div class="container" style="margin-top: 20px;"><div class="admin-alert">'.$_SESSION['msg'].'</div></div>';
    unset($_SESSION['msg']);
}
?>
<div class="section admin-theme-bg center" style="margin-top: 20px;">
    <h4>Add Doctor</h4>
</div>
<div class="container" style="margin-top: 30px; margin-bottom: 50px;">
    <div class="row">
        <div class="col s12 m8 offset-m2">
            <div class="card">
                <div class="card-content">
                    <form action="../backends/doctor-add.php" method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <div class="input-field col s12 m4">
                                <input type="text" id="name" name="name" class="validate" required>
                                <label for="name">Doctor Name</label>
                            </div>
                            <div class="input-field col s12 m4">
                                <input type="email" id="email" name="email" class="validate" required>
                                <label for="email">Email (for Login)</label>
                            </div>
                            <div class="input-field col s12 m4">
                                <input type="password" id="password" name="password" class="validate" required>
                                <label for="password">Password (for Login)</label>
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
                            <div class="input-field col s6">
                                <input type="number" id="fee" name="fee" class="validate" required>
                                <label for="fee">Consultation Fee (BDT)</label>
                            </div>
                            <div class="input-field col s6">
                                <select id="available_start" name="available_start" required>
                                    <option value="09:00" selected>09:00</option>
                                    <option value="10:00">10:00</option>
                                    <option value="11:00">11:00</option>
                                    <option value="14:00">14:00</option>
                                </select>
                                <label>Available From</label>
                            </div>
                            <div class="input-field col s6">
                                <select id="available_end" name="available_end" required>
                                    <option value="16:00">16:00</option>
                                    <option value="17:00" selected>17:00</option>
                                    <option value="18:00">18:00</option>
                                    <option value="20:00">20:00</option>
                                </select>
                                <label>Available Till</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="file-field input-field col s12">
                                <div class="btn" style="background: #6b9080 !important;">
                                    <span>Upload Photo (Optional)</span>
                                    <input type="file" name="image" accept="image/*">
                                </div>
                                <div class="file-path-wrapper">
                                    <input class="file-path validate" type="text" placeholder="Select doctor photo">
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
