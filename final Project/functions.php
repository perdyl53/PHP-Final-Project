<?php

function searchPhones($term, $database) {
	$term = $term . '%';
	$sql = file_get_contents('sql/getPhones.sql');
	$params = array(
		'term' => $term
	);
	$statement = $database->prepare($sql);
	$statement->execute($params);
	$phones = $statement->fetchAll(PDO::FETCH_ASSOC);
	return $phones;
}

function get($key) {
	if(isset($_GET[$key])) {
		return $_GET[$key];
	}
	else {
		return '';
	}
}