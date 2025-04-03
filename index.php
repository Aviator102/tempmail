<?php
error_reporting(0); //comment out for development server
//error_reporting(E_ALL);
//ini_set("display_errors", 1);

define("BASE_PATH", str_replace("\\", NULL, dirname($_SERVER["SCRIPT_NAME"]) == "/" ? NULL : dirname($_SERVER["SCRIPT_NAME"])) . '/');

require_once "core/router.php";

Router::init();

function __autoload($class) { Router::autoload($class); }

Router::route();
?>