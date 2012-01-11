<?php
require_once 'Web10/Common/CoreClassLoader.php';
$loader = new Web10\Common\CoreClassLoader();
$loader->setup();

use Web10\Common\CoreContainer;

$ioc = new CoreContainer();
$ioc->setupSAdmin();

session_start();

$twig = $ioc->get('Twig');

if (isset($_REQUEST['c']))
{
  $controller = $_REQUEST['c'];
  $method = $_REQUEST['m'];
  
  $c = $ioc->get("Web10\\SAdmin\\Controller\\{$controller}\\{$controller}Controller");
  $c->setTemplateEngine($twig);
  $c->setWebUtil($ioc->get('Web10\SAdmin\WebUtil'));
  $c->$method();
}
else
{
  print $twig->render('home.tpl', array('title' => 'Main Menu'));
}
?>
