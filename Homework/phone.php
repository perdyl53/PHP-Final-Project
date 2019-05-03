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