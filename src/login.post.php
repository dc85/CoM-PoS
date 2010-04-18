<?php

// @ error reporting setting  (  modify as needed )
ini_set("display_errors", 1);
error_reporting(E_ALL);

//@ explicity start session just if not automatically started at php.ini
session_start();

//@ validate inclusion
define('VALID_ACL_',		true);

//@ load dependency files
require('login.config.php');
require('login.lang.php');
require('login.class.php');

sleep(1); // do not use in production

//@ new acl instance
$acl = new Authorization;

//@check session status 
$status = $acl->check_status();

/*if($status)
	{
		// @ session already active
		echo "{'status':true,'message':'".$ACL_LANG['SESSION_ACTIVE']."','url':'".SUCCESS_URL."'}";
	}*/
if($status == "1")
	{
		// @ session already active
		echo "{'status':true,'message':'".$ACL_LANG['SESSION_ACTIVE']."','url':'".USER1_URL."'}";
	}
else if($status == "2")
	{
		// @ session already active
		echo "{'status':true,'message':'".$ACL_LANG['SESSION_ACTIVE']."','url':'".USER2_URL."'}";
	}
else if($status == "3")
	{
		// @ session already active
		echo "{'status':true,'message':'".$ACL_LANG['SESSION_ACTIVE']."','url':'".USER3_URL."'}";
	}
else if($status == "4")
	{
		// @ session already active
		echo "{'status':true,'message':'".$ACL_LANG['SESSION_ACTIVE']."','url':'".USER4_URL."'}";
	}
else if($status == "5")
	{
		// @ session already active
		echo "{'status':true,'message':'".$ACL_LANG['SESSION_ACTIVE']."','url':'".USER5_URL."'}";
	}
else
	{
		//@ session not active
		if($_SERVER['REQUEST_METHOD']=='GET')
			{
				//@ first load
				echo "{'status':false,'message':'".$acl->form()."'}";
			}
		else
			{
				//@ form submission
				$u = (!empty($_POST['u']))?trim($_POST['u']):false;	// retrive user var
				$p = (!empty($_POST['p']))?trim($_POST['p']):false;	// retrive password var
				$e = (!empty($_POST['e']))?trim($_POST['e']):false;	// retrive password var
								
				// @ try to signin
				$is_auth = $acl->signin($u,$p,$e);
				
				if($is_auth == "1")
					{
						//@ success
						echo "{'status': true,'message':'".$ACL_LANG['USER1']."','url':'".USER1_URL."'}";
					}
				else if($is_auth == "2")
					{
						echo "{'status': true,'message':'".$ACL_LANG['USER2']."','url':'".USER2_URL."'}";
					}
				else if($is_auth == "3")
					{
						echo "{'status': true,'message':'".$ACL_LANG['USER3']."','url':'".USER3_URL."'}";
					}
				else if($is_auth == "4")
					{
						echo "{'status': true,'message':'".$ACL_LANG['USER4']."','url':'".USER4_URL."'}";
					}
				else if($is_auth == "5")
					{
						echo "{'status': true,'message':'".$ACL_LANG['USER5']."','url':'".USER5_URL."'}";
					}
					
				else
					{
						//@ failed
						echo "{'status': false,'message':'".$ACL_LANG['LOGIN_FAILED']."'}";
					}
				
				/*if($is_auth)
					{
						//@ success
						echo "{'status': true,'message':'".$ACL_LANG['LOGIN_SUCCESS']."','url':'".SUCCESS_URL."'}";
					}
				else
					{
						//@ failed
						echo "{'status': false,'message':'".$ACL_LANG['LOGIN_FAILED']."'}";
					}*/
			}
	}

//@ destroy instance
unset($acl);
?>