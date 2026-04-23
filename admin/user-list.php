<?php require('layout/header.php'); ?>
<?php require('layout/left-sidebar-long.php'); ?>
<?php require('layout/topnav.php'); ?>
<?php require('layout/left-sidebar-short.php'); ?>


<?php

require('../backends/connection-pdo.php');

$sql = 'SELECT * FROM users';

$query  = $pdoconn->prepare($sql);
$query->execute();
$arr_all = $query->fetchAll(PDO::FETCH_ASSOC);



?>
						

<div class="section white-text" style="background: #B35458;">

	<div class="section">
		<h3>All Users</h3>
	</div>

  <?php

    if (isset($_SESSION['msg'])) {
        echo '<div class="section center" style="margin: 5px 35px;"><div class="row" style="background: red; color: white;">
        <div class="col s12">
            <h6>'.$_SESSION['msg'].'</h6>
            </div>
        </div></div>';
        unset($_SESSION['msg']);
    }

    ?>
	
	<div class="section center" style="padding: 20px;">
		<table class="centered responsive-table">
        <thead>
          <tr>
	      <th>ID</th>
              <th>Name</th>
              <th>Email</th>
              <th>Password</th>
              <th>Time Stamp</th>
              <th>Action</th>
          </tr>
        </thead>

        <tbody>
          <?php

            foreach ($arr_all as $key) {

          ?>
          <tr>
	    <td><?php echo $key['id']; ?></td>
	    <td><?php echo $key['name']; ?></td>
            <td><?php echo $key['email']; ?></td>
            <td><?php echo $key['password']; ?></td>
            <td><?php echo $key['timestamp']; ?></td>
	 
            <td><a href="../backends/admin/user-delete.php?id=<?php echo $key['id']; ?>"><span class="new badge" data-badge-caption="">Delete</span></a></td>
          </tr>

          <?php } ?>
         
        </tbody>
      </table>
	</div>
</div>

<?php require('layout/about-modal.php'); ?>
<?php require('layout/footer.php'); ?>