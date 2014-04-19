<?php

$obj->setCallback(BASE_URL.'confirm.php');
$url = $obj->getAuthorizeUrl();
$token = $obj->getToken();

$_SESSION['oauth_token'] = $token->oauth_token;
$_SESSION['oauth_token_secret'] = $token->oauth_token_secret;
//echo $_SESSION['oauth_token'].'----'.$_SESSION['oauth_token_secret'];

//header('location:'.$url);

?>
<div align="center">
	<a href="<?=$url?>"><img src="assets/images/linkedin_signin.png" /></a>
</div>