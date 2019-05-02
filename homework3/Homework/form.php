<?php

// Create and include a configuration file with the database connection
include('config.php');

// Include functions for application
include('functions.php');

// Get type of form either add or edit from the URL (ex. form.php?action=add) using the newly written get function
$action = $_GET['action'];

// Get the phone isbn from the URL if it exists using the newly written get function
$isbn = get('isbn');

// Initially set $phone to null;
$phone = null;

// Initially set $phone_categories to an empty array;
$phone_categories = array();

// If phone isbn is not empty, get phone record into $phone variable from the database
//     Set $phone equal to the first phone in $phones
// 	   Set $phone_categories equal to a list of categories associated to a phone from the database
if(!empty($isbn)) {
	$sql = file_get_contents('sql/getphone.sql');
	$params = array(
		'isbn' => $isbn
	);
	$statement = $database->prepare($sql);
	$statement->execute($params);
	$phones = $statement->fetchAll(PDO::FETCH_ASSOC);
	
	$phone = $phones[0];
	
	// Get phone categories
	$sql = file_get_contents('sql/getphoneCategories.sql');
	$params = array(
		'isbn' => $isbn
	);
	$statement = $database->prepare($sql);
	$statement->execute($params);
	$phone_categories_associative = $statement->fetchAll(PDO::FETCH_ASSOC);
	
	foreach($phone_categories_associative as $category) {
		$phone_categories[] = $category['categoryid'];
	}
}

// Get an associative array of categories
$sql = file_get_contents('sql/getCategories.sql');
$statement = $database->prepare($sql);
$statement->execute();
$categories = $statement->fetchAll(PDO::FETCH_ASSOC); 

// If form submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$isbn = $_POST['isbn'];
	$title = $_POST['phone-title'];
	$phone_categories = $_POST['phone-category'];
	$author = $_POST['phone-author'];
	$price = $_POST['phone-price'];
	
	if($action == 'add') {
		// Insert phone
		$sql = file_get_contents('sql/insertphone.sql');
		$params = array(
			'isbn' => $isbn,
			'title' => $title,
			'author' => $author,
			'price' => $price
		);
	
		$statement = $database->prepare($sql);
		$statement->execute($params);
		
		// Set categories for phone
		$sql = file_get_contents('sql/insertphoneCategory.sql');
		$statement = $database->prepare($sql);
		
		foreach($phone_categories as $category) {
			$params = array(
				'isbn' => $isbn,
				'categoryid' => $category
			);
			$statement->execute($params);
		}
	}
	
	elseif ($action == 'edit') {
		$sql = file_get_contents('sql/updatephone.sql');
        $params = array( 
            'isbn' => $isbn,
            'title' => $title,
            'author' => $author,
            'price' => $price
        );
        
        $statement = $database->prepare($sql);
        $statement->execute($params);
        
        //remove current category info 
        $sql = file_get_contents('sql/removeCategories.sql');
        $params = array(
            'isbn' => $isbn
        );
        
        $statement = $database->prepare($sql);
        $statement->execute($params);
        
        //set categories for phone
        $sql = file_get_contents('sql/insertphoneCategory.sql');
        $statement = $database->prepare($sql);
        
        foreach($phone_categories as $category) {
            $params = array(
                'isbn' => $isbn,
                'categoryid' => $category
            );
            $statement->execute($params);
        };	
	}
	
	// Redirect to phone listing page
	header('location: index.php');
}

// In the HTML, if an edit form:
	// Populate textboxes with current data of phone selected 
	// Print the checkbox with the phone's current categories already checked (selected)
?>

<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	
  	<title>Manage phone</title>
	<meta name="description" content="The HTML5 Herald">
	<meta name="author" content="SitePoint">

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
				<label>ISBN:</label>
				<?php if($action == 'add') : ?>
					<input type="text" name="isbn" class="textbox" value="<?php echo $phone['isbn'] ?>" />
				<?php else : ?>
					<input readonly type="text" name="isbn" class="textbox" value="<?php echo $phone['isbn'] ?>" />
				<?php endif; ?>
			</div>
			<div class="form-element">
				<label>Title:</label>
				<input type="text" name="phone-title" class="textbox" value="<?php echo $phone['title'] ?>" />
			</div>
			<div class="form-element">
				<label>Category:</label>
				<?php foreach($categories as $category) : ?>
					<?php if(in_array($category['categoryid'], $phone_categories)) : ?>
						<input checked class="radio" type="checkbox" name="phone-category[]" value="<?php echo $category['categoryid'] ?>" /><span class="radio-label"><?php echo $category['name'] ?></span><br />
					<?php else : ?>
						<input class="radio" type="checkbox" name="phone-category[]" value="<?php echo $category['categoryid'] ?>" /><span class="radio-label"><?php echo $category['name'] ?></span><br />
					<?php endif; ?>
				<?php endforeach; ?>
			</div>
			<div class="form-element">
				<label>Author</label>
				<input type="text" name="phone-author" class="textbox" value="<?php echo $phone['author'] ?>" />
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