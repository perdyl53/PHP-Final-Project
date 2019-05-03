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
?>

<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	
  	<title>Manage phone</title>
	<meta name="description" content="The HTML5 Herald">
	<meta name="brand" content="SitePoint">

	<link rel="stylesheet" href="css/style.css">
	<!--[if lt IE 9]>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.js"></script>
  	<![endif]-->
</head>
<body>
	<div class="page">
		<h1>Manage phone</h1>
		<form action="" method="POST">
			<div class="form-element">
				<label>name:</label>
				<input type="text" name="phone-name" class="textbox" value="<?php echo $phone['name'] ?>" />
			</div>
			<div class="form-element">
				<label>brand</label>
				<input type="text" name="phone-brand" class="textbox" value="<?php echo $phone['brand'] ?>" />
			</div>
			<div class="form-element">
				<label>ptype:</label>
				<?php if($action == 'add') : ?>
					<input type="text" name="ptype" class="textbox" value="<?php echo $phone['ptype'] ?>" />
				<?php else : ?>
					<input readonly type="text" name="ptype" class="textbox" value="<?php echo $phone['ptype'] ?>" />
				<?php endif; ?>
			</div>
			<div class="form-element">
				<label>Price:</label>
				<input type="number" step="any" name="phone-price" class="textbox" value="<?php echo $phone['price'] ?>" />
			</div>
			<div class="form-element">
				<input type="submit" class="button" />&nbsp;
				<input type="reset" class="button" />
			</div>
		</form>
	</div>
</body>
</html>