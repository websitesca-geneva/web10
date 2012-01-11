<?php
require_once 'Web10/Common/CoreClassLoader.php';
$loader = new Web10\Common\CoreClassLoader();
$loader->setup();

use \Exception;
use Web10\Web\BadControllerRequestException;
use Web10\Common\CoreContainer;
use Web10\Web\EditingPageHelper;
use Web10\Web\WebHelper;

//write sessionid cookie to response
$container = CoreContainer::getStatic();
$helper = $container->get('Web10\Business\CookieHelper');
$sessionId = $helper->setupVisitor();

//setup VisitorContext
$vc = $container->get('Web10\Common\Contexts\VisitorContext');
$vc->setupBySessionId($sessionId);

//setup AccountContext
$ac = $container->get('Web10\Common\Contexts\AccountContext');
$ac->setupByHostname($_SERVER['HTTP_HOST']);

$auth = $container->get('Web10\Business\Authentication');

if (isset($_REQUEST["action"]) and $_REQUEST["action"] = "logout")
{
  $auth->logout();
  WebHelper::redirect("/");
}
else if (isset($_REQUEST["submit"]))
{
  $email = $_REQUEST["email"];
  $password = $_REQUEST["password"];

  $message = "";
  if ($auth->login($email, $password))
  WebHelper::redirect("/");
  else
  $message = "Email/Password combination not found.";
}

$helper = new EditingPageHelper();
print $helper->getHeader("Login");

if (!empty($message)) print "<span class='error'>$message</span>";
?>

<form method="post" action="/login.php">
<table>
  <tr>
    <td>Email</td>
    <td><input type="text" name="email"></td>
  </tr>
  <tr>
    <td>Password</td>
    <td><input type="password" name="password"></td>
  </tr>
  <tr>
    <td colspan="2"><input type="submit" name="submit" value="Login"></td>
  </tr>
</table>
</form>

<?
print $helper->getFooter();
?>
