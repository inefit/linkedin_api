<?php

include_once('global.php');

// Cek session
if(!isset($_SESSION['oauth_token']) OR !isset($_SESSION['oauth_token_secret']) OR !isset($_SESSION['linkedin_user_id'])){
	$users = $db->get_single_data('user' , "id='0'");
	
	$_SESSION['oauth_token'] = $users['token'];
	$_SESSION['oauth_token_secret'] = $users['tokenSecret'];
	$_SESSION['linkedin_user_id'] = $users['userId'];
}
$obj->setToken($_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);

// Select Category


?>