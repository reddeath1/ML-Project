<?php
session_start();
$_SESSION = array();
function current_page_url(){
    $page_url   = 'http';
    if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on'){
        $page_url .= 's';
    }
    return $page_url.'://'.$_SERVER['SERVER_NAME'];
}


$myAddress = current_page_url();
if(preg_match("/localhost/",$myAddress)){
$myAddress = current_page_url()."/ML Project";
}

if(isset($_SESSION['ml_id']) && isset($_SESSION['ml_user'])) {
	session_destroy();
}

setcookie('ml_id', '', '-3 days', '/','', '', TRUE);
setcookie('ml_user', '', '-3 days', '/','', '', TRUE);


if(isset($_SESSION['ml_id']) && isset($_SESSION['ml_user'])) {
	echo "<h3>Logout out failed !</h3>";
}else {
	header("Location:".$myAddress);
}


?>