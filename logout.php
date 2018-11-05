<?php

$_SESSION = array();

include_once ("config/Config.php");


if(isset($_SESSION['ml_id']) && isset($_SESSION['ml_user'])) {
    session_destroy();
}

setcookie('ml_id', '', time() - 3600,'/');
setcookie('ml_user', '', time() - 3600,'/');


if(isset($_SESSION['ml_id']) || isset($_COOKIE['ml_user'])) {
    echo "<h3>Logout failed !</h3>";
    echo "<script> window.location.reload(true); </script>";
}else {
   
    echo "<script> window.location.href = '".URI."' </script>";
}
?>
