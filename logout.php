<?php
	include_once('global.php');
	session_destroy();
	header('location:'.BASE_URL);
?>