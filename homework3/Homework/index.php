<?php

// Create and include a configuration file with the database connection
include('config.php');

// Include functions for application
include('functions.php');

// Get search term from URL using the get function
$term = get('search-term');

// Get a list of phones using the searchphones function
// Print the results of search results
// Add a link printed for each phone to phone.php with an passing the isbn
// Add a link printed for each phone to form.php with an action of edit and passing the isbn
$phones = searchphones($term, $database);
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
		<?php foreach($phones as $phone) : ?>
			<p>
				<?php echo $phone['title']; ?><br />
				<?php echo $phone['author']; ?> <br />
				<?php echo $phone['price']; ?> <br />
				<a href="form.php?action=edit&isbn=<?php echo $phone['isbn'] ?>">Edit phone</a><br />
				<a href="phone.php?isbn=<?php echo $phone['isbn'] ?>">View phone</a>
			</p>
		<?php endforeach; ?>
		
		<!-- print currently accessed by the current username -->
		<p>Currently logged in as: <?php echo $client->getName() ?></p>
		
		<!-- A link to the logout.php file -->
		<p>
			<a href="logout.php">Log Out</a>
		</p>
	</div>
</body>
</html>