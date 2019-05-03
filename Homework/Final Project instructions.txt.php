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
// Get ptype of form either add or edit from the URL (ex. form.php?action=add) using the newly written get function
$action = $_GET['action'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$ptype = $_POST['ptype'];
	$name = $_POST['phone-name'];
	$phone_categories = $_POST['phone-category'];
	$brand = $_POST['phone-brand'];
	$price = $_POST['phone-price'];
	
	if($action == 'add') {
		// Insert phone
		$sql = file_get_contents('sql/insertphone.sql');
		$params = array(
			'ptype' => $ptype,
			'name' => $name,
			'brand' => $brand,
			'price' => $price
		);
	
		$statement = $database->prepare($sql);
		$statement->execute($params);
		
		// Set categories for phone
		$sql = file_get_contents('sql/insertphoneCategory.sql');
		$statement = $database->prepare($sql);
		
		foreach($phone_categories as $category) {
			$params = array(
				'ptype' => $ptype,
				'categoryid' => $category
			);
			$statement->execute($params);
		}
	}
	
	elseif ($action == 'edit') {
		$sql = file_get_contents('sql/updatephone.sql');
        $params = array( 
            'ptype' => $ptype,
            'name' => $name,
            'brand' => $brand,
            'price' => $price
        );
        
        $statement = $database->prepare($sql);
        $statement->execute($params);
        
        //remove current category info 
        $sql = file_get_contents('sql/removeCategories.sql');
        $params = array(
            'ptype' => $ptype
        );
        
        $statement = $database->prepare($sql);
        $statement->execute($params);
        
        //set categories for phone
        $sql = file_get_contents('sql/insertphoneCategory.sql');
        $statement = $database->prepare($sql);
        
        foreach($phone_categories as $category) {
            $params = array(
                'ptype' => $ptype,
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
				<label>ptype:</label>
				<?php if($action == 'add') : ?>
					<input ptype="text" name="ptype" class="textbox" value="<?php echo $phone['ptype'] ?>" />
				<?php else : ?>
					<input readonly ptype="text" name="ptype" class="textbox" value="<?php echo $phone['ptype'] ?>" />
				<?php endif; ?>
			</div>
			<div class="form-element">
				<label>name:</label>
				<input ptype="text" name="phone-name" class="textbox" value="<?php echo $phone['name'] ?>" />
			</div>
			<div class="form-element">
				<label>Category:</label>
				<?php foreach($categories as $category) : ?>
					<?php if(in_array($category['categoryid'], $phone_categories)) : ?>
						<input checked class="radio" ptype="checkbox" name="phone-category[]" value="<?php echo $category['categoryid'] ?>" /><span class="radio-label"><?php echo $category['name'] ?></span><br />
					<?php else : ?>
						<input class="radio" ptype="checkbox" name="phone-category[]" value="<?php echo $category['categoryid'] ?>" /><span class="radio-label"><?php echo $category['name'] ?></span><br />
					<?php endif; ?>
				<?php endforeach; ?>
			</div>
			<div class="form-element">
				<label>brand</label>
				<input ptype="text" name="phone-brand" class="textbox" value="<?php echo $phone['brand'] ?>" />
			</div>
			<div class="form-element">
				<label>Price:</label>
				<input ptype="number" step="any" name="phone-price" class="textbox" value="<?php echo $phone['price'] ?>" />
			</div>
			<div class="form-element">
				<input ptype="submit" class="button" />&nbsp;
				<input ptype="reset" class="button" />
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
SELECT orders.orderid, clients.clientid, clients.name, orders.amount, phones.ptype, phones.price
FROM clients
INNER JOIN orders on clients.clientid=orders.clientid inner join phones on phones.phoneid=orders.phoneid

Step 7:









