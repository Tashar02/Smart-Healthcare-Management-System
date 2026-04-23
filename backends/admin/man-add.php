<?php


session_start();
try {

    if (!file_exists('../connection-pdo.php' ))
        throw new Exception();
    else
        require_once('../connection-pdo.php' ); 
		
} catch (Exception $e) {

	$_SESSION['msg'] = 'There were some problem in the Server! Try after some time!';

	header('location: ../../admin/man-list.php');

	exit();
	
}

if (!isset($_POST['name']) || !isset($_POST['address'])) {

	$_SESSION['msg'] = 'Invalid POST variable keys! Refresh the page!';

	header('location: ../../admin/man-list.php');

	exit();
}

$regex = '/^[(A-Z)?(a-z)?(0-9)?\-?\_?\.?\s*]+$/';


if (!preg_match($regex, $_POST['name']) || !preg_match($regex, $_POST['address'])) {

	$_SESSION['msg'] = 'Whoa! Invalid Inputs!';

	header('location: ../../admin/man-list.php');

	exit();

} else {

	$name = $_POST['name'];
	$address = $_POST['address'];
	$phone = $_POST['phone'];

	$sql = "INSERT INTO delivary(name,address,phone) VALUES(?,?,?)";
    $query  = $pdoconn->prepare($sql);
    if ($query->execute([ $name, $address ,$phone])) {

    	$_SESSION['msg'] = 'Delivary Man Added!';

		header('location: ../../admin/man-list.php');
    	
    } else {

    	$_SESSION['msg'] = 'There were some problem in the server! Please try again after some time!';

		header('location: ../../admin/man-list.php');

    }


}