<?php

//@ validate inclusion
if(!defined('VALID_ACL_')) exit('direct access is not allowed.');

$ACL_LANG = array (
		'USER1'				=>	'You have successfuly authorized(L1), click <a href="'.USER1_URL.'">here</a> to continue.',
		'USER2'				=>	'You have successfuly authorized(L2), click <a href="'.USER2_URL.'">here</a> to continue.',
		'USER3'				=>	'You have successfuly authorized(L3), click <a href="'.USER3_URL.'">here</a> to continue.',
		'USER4'				=>	'You have successfuly authorized(L4), click <a href="'.USER4_URL.'">here</a> to continue.',
		'USER5'				=>	'You have successfuly authorized(L5), click <a href="'.USER5_URL.'">here</a> to continue.',
		'USERNAME'			=>	'Username',
		'EMAIL'				=>	'E-mail',
		'PASSWORD'			=>	'Password',
		'LOGIN'				=>	'Login!',
		'EMAIL'				=>	'E-mail',
		'RESET'				=>	'Reset!',
		'SESSION_ACTIVE'	=>	'Your Session is already active, click <a href="'.SUCCESS_URL.'">here</a> to continue.',
		'LOGIN_SUCCESS'		=>	'You have successfuly authorized, click <a href="'.SUCCESS_URL.'">here</a> to continue.',
		'LOGIN_FAILED'		=>	'Login Failed: wrong combination of '.((LOGIN_METHOD=='user'||LOGIN_METHOD=='both')?'Username':''). 
								((LOGIN_METHOD=='both')?'/':'').
								((LOGIN_METHOD=='email'||LOGIN_METHOD=='both')?'email':'').
								' and password.',
	);
?>