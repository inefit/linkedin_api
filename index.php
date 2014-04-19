<?php

include_once('global.php');

include_once('templates/header.html');
if(isset($_SESSION['oauth_token']) && isset($_SESSION['oauth_token_secret']) && isset($_SESSION['linkedin_user_id'])){
	$obj->setToken($_SESSION['oauth_token'],$_SESSION['oauth_token_secret']);
	include_once('main.php');
}
else{
	include_once('login.php');
}

include_once('templates/footer.html');
?>