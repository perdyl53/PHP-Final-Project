<?php

include('config.php');

include('functions.php');

$ptype = get('ptype');

$sql = file_get_contents('sql/getPhone.sql');
$params = array(
	'ptype' => $ptype
);
$statement = $database->prepare($sql);
$statement->execute($params);
$phones = $statement->fetchAll(PDO::FETCH_ASSOC);

$phone = $phones[0];
?>

<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	
  	<title>phone</title>
	<meta name="description" content="The HTML5 Herald">
	<meta name="brand" content="SitePoint">

	<link rel="stylesheet" href="css/style.css">

	<!--[if lt IE 9]>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.js"></script>
  	<![endif]-->
</head>
<body>
	<div class="page">
		<h1><?php echo $phone['name'] ?></h1>
		<p>
			<?php echo $phone['brand']; ?><br />
			<?php echo $phone['price']; ?><br />
		</p>
		
	</div>
</body>
</html>