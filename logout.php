<?php

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

include_once ("config/Config.php");


if(isset($_SESSION['ml_id']) && isset($_SESSION['ml_user'])) {
    session_destroy();
}

setcookie('ml_id', '', time() - 3600,'/');
setcookie('ml_user', '', time() - 3600,'/');


if(isset($_SESSION['ml_id']) || isset($_COOKIE['ml_user'])) {
    echo "<h3>Logout out failed !</h3>";
    echo "<script> window.location.reload(true); </script>";
}else {
   
    echo "<script> window.location.href = '$myAddress' </script>";
}
?>