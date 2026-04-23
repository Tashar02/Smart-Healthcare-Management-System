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

if (!isset($_REQUEST['id'])) {

	$_SESSION['msg'] = 'Invalid ID!';

	header('location: ../../admin/man-list.php');

	exit();
} 

	$id = $_REQUEST['id'];


	$sql = "DELETE FROM delivary WHERE id = ?";
    $query  = $pdoconn->prepare($sql);
    if ($query->execute([$id])) {

    	$_SESSION['msg'] = 'Delivary Man Deleted!';

		header('location: ../../admin/man-list.php');
    	
    } else {

    	$_SESSION['msg'] = 'There were some problem in the server! Please try again after some time!';

		header('location: ../../admin/man-list.php');

    }

