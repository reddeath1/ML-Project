<?php
/**
 * Created by PhpStorm.
 * User: reddeath
 * Date: 10/27/2018
 * Time: 10:46 PM
 */

/**
 * Disable caching controll
 */

$title = "ML Project";
header("Expires: Tue, 03 Jul 2001 06:00:00 GMT"); // Read (1)
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");


define('DIR',__DIR__);
define('URI',(preg_match('/localhost/',url())) ? url().'/ML Project' : url());
define('ROOT',(preg_match('/localhost/',url())) ? $_SERVER['DOCUMENT_ROOT'].'/ML Project' : $_SERVER['DOCUMENT_ROOT']);

$default_user = 'Guest';

require_once (ROOT.'/core/session.php');

$isLoggedIn = $session->loggedIn;
$ud = $session->id;
$user = $session->user;
$first_name = $session->first_name;
$email = $session->email;

require_once (ROOT.'/core/Core.php');

function uid(){
    return $session->id;
}

$default_user = ($isLoggedIn) ? $first_name : $default_user;
$isLoggedIn = ($isLoggedIn) ? 1 : 0;
$url = URI;
echo "<script>
var user = {isLoggedIn:$isLoggedIn,u:'$ud',un:'$user',url:'$url'};
</script>";

function url(){
    $page_url   = 'http';
    if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on'){
        $page_url .= 's';
    }
    $page_url = $page_url.'://'.$_SERVER['SERVER_NAME'];

    return $page_url;
}
