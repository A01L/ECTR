<?php 
header('X-Frame-Options: SAMEORIGIN');
error_reporting(E_ERROR | E_PARSE);
ini_set('session.gc_maxlifetime', 172800);
ini_set('session.cookie_lifetime', 172800);
$ectr_host=" ";
$ectr_db="brc";
$ectr_login="root";
$ectr_password="";
$ectr_charset = "utf8";

if ($ectr_host == " ") {
    $ectr_connect = null;
}
else {
    $ectr_connect = mysqli_connect($ectr_host, $ectr_login, $ectr_password, $ectr_db);
        // mysqli_set_charset($ectr_connect, $ectr_charset);
        if (!$ectr_connect) {
            die('Error connect to Data base!');
        }
}

$ectr_appname="";
$ectr_author="";

 ?>