<?php
//@ validate inclusion
if(!defined('VALID_ACL_')) exit('direct access is not allowed.');

define('USEDB',			true);				//@ use database? true:false
define('LOGIN_METHOD',	'old');			//@ 'user':'email','both'
define('SUCCESS_URL',	'../php/product.php');		//@ redirection target on success
define('TEST_URL',	'../php/timesheet.php');		//@ redirection target on success
define('USER1_URL',		'../php/timesheet.php');
define('USER2_URL',		'../php/timesheet.php');
define('USER3_URL',		'../php/timesheet.php');
define('USER4_URL',		'../php/timesheet.php');
define('USER5_URL',		'../php/timesheet.php');

//@ you could delete one of block below according to the USEDB value
if(USEDB) 
	{
		$db_config = array(
				'server'	=>	'localhost',	//@ server name or ip address along with suffix ':port' if needed (localhost:3306)
				'user'		=>	'SQL',			//@ mysql username
				'pass'		=>	'pos',	//@ mysql password
				'name'		=>	'pos',		//@ database name
				'tbl_user'	=>	'tblStaff'		//@ user table name
			);
	}
else
	{
		$user_config = array(
			array(
				'username'	=>	'admin',
				'useremail'	=>	'admin@localhost',
				'userpassword'	=>	'e10adc3949ba59abbe56e057f20f883e',	// md5:123456
			),
			array(
				'username'	=>	'user',
				'useremail'	=>	'user@localhost',
				'userpassword'	=>	'e10adc3949ba59abbe56e057f20f883e',	// md5:123456
			)	
		);
	}
?>