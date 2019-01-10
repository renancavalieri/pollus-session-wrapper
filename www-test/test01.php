<?php ini_set("display_errors", 1);

require_once (__DIR__."/../vendor/autoload.php");

use Pollus\SessionWrapper\Session;

$session = new Session();
$session->name("test_session");
$session->start();
$str_test = $session->get("str_test");

echo "Test 01: ";

if ($str_test == "Hello World")
{
    echo $str_test . " - Passed";
}
else
{
    var_dump($_SESSION);
    die("- Failed: The value of str_test was not 'Hello World'");
}

$session->commit();
header('Location: test02.php');