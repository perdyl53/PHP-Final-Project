<?php

$user = 'perezd4';
$password = 'Xu4renep';

$database = new PDO('mysql:host=csweb.hh.nku.edu;dbname=db_spring19_perezd4', $user, $password);

session_start();

$current_url = basename($_SERVER['REQUEST_URI']);
function dylan_autoloader($class) {
    include "class." . $class . ".php";
}

spl_autoload_register('dylan_autoloader');

$client_New = new NewFname();
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