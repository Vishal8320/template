<?php
   
   require_once(__DIR__ . '/config.php');
   session_set_cookie_params(null,COOKIE_PATH);
   session_start();
   //require_once(__DIR__ .'/../Languages/english.php');
   require_once(__DIR__ .'/database.php');
   require_once(__DIR__ .'/skins.php');
   require_once(__DIR__ .'/functions.php');
   require_once(__DIR__ .'/classes.php');
   require_once(__DIR__ .'/misc.php');
   require_once(getLanguage(null,(isset($_GET['lang']) && !empty($_GET['lang'])? $_GET['lang']: (isset($_COOKIE['lang'])? $_COOKIE['lang']:null)),null));
 


?>