<?php
Step 1:
- A configuration file that connects to a database, includes functions.php and autoloads classes
*********
include('functions.php');

$user = 'perezd4';
$password = 'Xu4renep';

$database = new PDO('mysql:host=csweb.hh.nku.edu;dbname=db_spring19_perezd4', $user, $password);

function dylan_autoloader($class) {
    include "class." . $class . ".php";
}

spl_autoload_register('dylan_autoloader');

session_start();
$client = new client($_SESSION["clientid"]);
$current_url = basename($_SERVER['REQUEST_URI']);

if (!isset($_SESSION["clientid"]) && $current_url != 'login.php') {
    header("Location: login.php");
}
elseif (isset($_SESSION["clientid"])) {
	$sql = file_get_contents('sql/getclient.sql');
	$params = array(
		'clientid' => $_SESSION["clientid"]
	);
	$statement = $database->prepare($sql);
	$statement->execute($params);
	$clients = $statement->fetchAll(PDO::FETCH_ASSOC);
	
	$client = $clients[0];
}

Step 2:
- Two forms that support add/edit functionality

********************
// Get type of form either add or edit from the URL (ex. form.php?action=add) using the newly written get function
$action = $_GET['action'];

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
?>
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

Step 3: 
- At least one listing page that is searchable
************

Step 4:
- Usage of session variables somehow in the application
************
if (!isset($_SESSION["clientid"]) && $current_url != 'login.php') {
    header("Location: login.php");
}
elseif (isset($_SESSION["clientid"])) {
	$sql = file_get_contents('sql/getclient.sql');
	$params = array(
		'clientid' => $_SESSION["clientid"]
	);
	$statement = $database->prepare($sql);
	$statement->execute($params);
	$clients = $statement->fetchAll(PDO::FETCH_ASSOC);
	
	$client = $clients[0];
}

Step 5:
- database with at least 3 tables, 2 or which are related
*********
DONE

Step 6:
- Use an SQL query with a JOIN statement
*********
SELECT orders.orderid, clients.clientid, clients.name, orders.amount, phones.type, phones.price
FROM clients
INNER JOIN orders on clients.clientid=orders.clientid inner join phones on phones.phoneid=orders.phoneid

Step 7:









