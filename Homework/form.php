<?php

include('config.php');

include('functions.php');

$action = $_GET['action'];

$ptype = get('ptype');

$phone = null;

if(!empty($ptype)) {
	$sql = file_get_contents('sql/getphone.sql');
	$params = array(
		'ptype' => $ptype
	);
	$statement = $database->prepare($sql);
	$statement->execute($params);
	$phones = $statement->fetchAll(PDO::FETCH_ASSOC);
	
	$phone = $phones[0];
	
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$name = $_POST['phone-name'];
	$brand = $_POST['phone-brand'];
	$ptype = $_POST['ptype'];
	$price = $_POST['phone-price'];
	
	if($action == 'add') {
		$sql = file_get_contents('sql/insertphone.sql');
		$params = array(
			'name' => $name,
			'brand' => $brand,
			'ptype' => $ptype,
			'price' => $price
		);
	
		$statement = $database->prepare($sql);
		$statement->execute($params);
		
	}
	
	elseif ($action == 'edit') {
		$sql = file_get_contents('sql/updatephone.sql');
        $params = array( 
            'name' => $name,
            'brand' => $brand,
			'ptype' => $ptype,
            'price' => $price
        );
        
        $statement = $database->prepare($sql);
        $statement->execute($params);
        
	}
	header('location: index.php');
}