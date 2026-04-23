<section class="navbar-section">
    <nav class="white" role="navigation">
        <div class="nav-wrapper container">
            <a id="logo-container" href="index.php" class="brand-logo" style="color: #6b9080; font-weight: 600;">HealthCare MS</a>
            <ul class="right hide-on-med-and-down">
                <li><a href="index.php" class="hvr-grow">Home</a></li>
                <li><a href="departments.php" class="hvr-grow">Departments</a></li>
                <li><a href="doctors.php" class="hvr-grow">Doctors</a></li>
                <li><a href="appointments.php" class="hvr-grow">Appointments</a></li>
                <li><a href="#" onclick="toggleModal('Contact Info', 'Call us at +8801925221970 for immediate assistance.');" class="hvr-grow">Contact</a></li>
                <?php
                if (isset($_SESSION['user'])) {
                    echo '<li><a href="my-appointments.php" class="hvr-grow">My Appointments</a></li>';
                    echo '<li><a href="#" class="hvr-grow">Hi, ' . $_SESSION['user'] . '</a></li>';
                    echo '<li><a href="logout.php" class="hvr-grow">Logout</a></li>';
                } else {
                    echo '<li><a href="#" class="hvr-grow modal-trigger" data-target="modal1">Login</a></li>';
                    echo '<li><a href="#" class="hvr-grow modal-trigger" data-target="modal2">Register</a></li>';
                }
                ?>
            </ul>
        </div>
    </nav>
    <ul class="sidenav" id="mobile-demo">
        <li><a href="index.php">Home</a></li>
        <li><a href="departments.php">Departments</a></li>
        <li><a href="doctors.php">Doctors</a></li>
        <li><a href="appointments.php">Appointments</a></li>
        <li><a href="#" onclick="toggleModal('Contact Info', 'Call us at +8801925221970 for immediate assistance.');">Contact</a></li>
        <?php
        if (isset($_SESSION['user'])) {
            echo '<li><a href="my-appointments.php">My Appointments</a></li>';
            echo '<li><a href="#">Hi, ' . $_SESSION['user'] . '</a></li>';
            echo '<li><a href="logout.php">Logout</a></li>';
        } else {
            echo '<li><a href="#" class="modal-trigger" data-target="modal1">Login</a></li>';
            echo '<li><a href="#" class="modal-trigger" data-target="modal2">Register</a></li>';
        }
        ?>
    </ul>
</section>
