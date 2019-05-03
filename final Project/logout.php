<?php

include('config.php');

include('functions.php');

session_destroy();

header('location: login.php');
?>