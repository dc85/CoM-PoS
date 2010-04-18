<?php
// @ error reporting setting  (  modify as needed )
ini_set("display_errors", 1);
error_reporting(E_ALL);

//@ explicity start session  ( remove if needless )
session_start();

//@ if logoff
if(!empty($_GET['logoff'])) { $_SESSION = array(); }

//@ is authorized?
if(empty($_SESSION['exp_user']) || @$_SESSION['exp_user']['expires'] < time()) {
	header("location:login.html");	//@ redirect 
} else {
	$_SESSION['exp_user']['expires'] = time()+(45*60);	//@ renew 45 minutes
}	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>PHP ajax login form using jquery</title>
<style type="text/css">
body { background:#ccc; text-align: center; font:normal 11px/normal arial; color:#333; padding:50px; }
#wrapper { width:400px; background:#666; border:solid 1px #fff; margin:0px auto; padding:15px;}
a{ color:#333; text-decoration:none; border-bottom:dotted 1px #666; }
a:hover { color:#000; }
</style>
</head>
<body>
	<div id="wrapper">
		<p>
		<a href="index.php?logoff=1">Logout</a>
		</p>
	</div>
</body>
</html>
