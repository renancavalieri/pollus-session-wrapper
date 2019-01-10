<?php ini_set("display_errors", 1);

require_once (__DIR__."/../vendor/autoload.php");

use Pollus\SessionWrapper\Session;
$session = new Session();
$session->unset();
$session->name("test_session");
$session->start();
$session->set("str_test", "Hello World");
$session->commit();
header('Location: test01.php');