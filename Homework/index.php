<?php

include('config.php');

include('functions.php');

$term = get('search-term');

$phones = searchphones($term, $database);
?>