<?php

require 'CheckSession.php';

session_start();

date_default_timezone_set('Asia/Jakarta');

// remove a session that not active more than 2 minutes
CheckSession::removeSession(2);

// check if session is full
// only 2 allowed
if(CheckSession::isFull(2)){
	echo "Sorry, the user has full";
}else{
	$_SESSION['user'] = rand(1, 10);
}

if(isset($_SESSION['user'])){
	echo 'User has login';
	echo '<br />';
	echo CheckSession::countSession();
	echo '<br />';
}