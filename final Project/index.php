<?php

include('config.php');

include('functions.php');

$phoneSearchTerm = '';
if (isset($_GET['search-term'])) {
	$phoneSearchTerm = $_GET['search-term'];

$phones = searchPhones($term, $database);
}
?>

<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
  	<title>phones</title>
	<meta name="description" content="The HTML5 Herald">
	<meta name="author" content="SitePoint">
	<link rel="stylesheet" href="css/style.css">
	<!--[if lt IE 9]>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.js"></script>
  	<![endif]-->
</head>
<body>
	<div class="page">
		<h1>phones</h1>
		<form method="GET">
			<input type="text" name="search-term" placeholder="Search..." />
			<input type="submit" />
		</form>
		
<?php
$sql = file_get_contents('sql/searchPhonesProvided.sql');
	$params = array(
		'phoneSearchTerm' => '%' . $phoneSearchTerm . '%' 
		);
	$statement = $database->prepare($sql);
	$statement->execute($params);
	
	$phones = $statement->fetchAll(PDO::FETCH_ASSOC); ?>
		<?php foreach($phones as $phone) : ?>
			<p>
				<?php echo $phone['name']; ?><br />
				<?php echo $phone['brand']; ?> <br />
				<?php echo $phone['price']; ?> <br />
				<a href="form.php?action=edit&ptype=<?php echo $phone['ptype'] ?>">Edit phone</a><br />
				<a href="phone.php?ptype=<?php echo $phone['ptype'] ?>">View phone</a>
			</p>
		<?php endforeach; ?>
		
		<p>Currently logged in as: <?php echo $client['fname'] ?></p>
		
		<p>
			<a href="logout.php">Log Out</a>
		</p>
	</div>
</body>
</html>