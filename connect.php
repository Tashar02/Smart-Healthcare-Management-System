<?php
require_once('config.php');
?>
<?php
   if(isset($_POST)){
	$name = $_POST['name'];
	$address = $_POST['address'];
	$number = $_POST['number'];
	$orderitem = $_POST['orderitem'];
	$quantity = $_POST['quantity'];
	$paymentmethod = $_POST['paymentmethod'];
	$tarnsectionid = $_POST['tarnsectionid'];
	$price = $_POST['price'];

		$sql = "INSERT INTO orders(name, address, number, orderitem, quantity, paymentmethod, tarnsectionid, price) VALUES(?,?,?,?,?,?,?,?)";
		$stmtinsert = $db->prepare($sql);
		$result = $stmtinsert->execute([$name, $address, $number, $orderitem, $quantity, $paymentmethod, $tarnsectionid, $price]);
		if($result){
			echo 'Successfully saved.';
		}else{
			echo 'There were erros while saving the data.';
		}
}else{
	echo 'No data';
}
?>