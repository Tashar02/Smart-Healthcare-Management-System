<?php

session_start();
try {

    if (!file_exists('../connection-pdo.php' ))
        throw new Exception();
    else
        require_once('../connection-pdo.php' ); 
		
} catch (Exception $e) {

	$_SESSION['msg'] = 'There were some problem in the Server! Try after some time!';

	header('location: ../../admin/category-list.php');

	exit();
	
}

if (!isset($_POST['c_name']) || !isset($_POST['short_desc']) || !isset($_POST['long_desc'])) {

	$_SESSION['msg'] = 'Invalid POST variable keys! Refresh the page!';

	header('location: ../../admin/category-list.php');

	exit();
}

$regex = '/^[(A-Z)?(a-z)?(0-9)?\-?\_?\.?\,?\s*]+$/';


if (!preg_match($regex, $_POST['c_name']) || !preg_match($regex, $_POST['short_desc']) || !preg_match($regex, $_POST['long_desc'])) {

	$_SESSION['msg'] = 'Whoa! Invalid Inputs!';

	header('location: ../../admin/category-list.php');

	exit();

} else {

	$c_name = $_POST['c_name'];
	$short_desc = $_POST['short_desc'];
	$long_desc = $_POST['long_desc'];

	$sql = "INSERT INTO categories(c_name,short_desc,long_desc) VALUES(?,?,?)";
    $query  = $pdoconn->prepare($sql);
    if ($query->execute([$c_name, $short_desc, $long_desc])) {

    	$_SESSION['msg'] = 'Category Added!';

		header('location: ../../admin/category-list.php');
    	
    } else {

    	$_SESSION['msg'] = 'There were some problem in the server! Please try again after some time!';

		header('location: ../../admin/category-list.php');

    }


}