<?php
require_once 'Web10/Common/CoreClassLoader.php';
$loader = new Web10\Common\CoreClassLoader();
$loader->setup();

use \Exception;
use Web10\Web\BadControllerRequestException;
use Web10\Common\CoreContainer;

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

$dispatchType = strtoupper($_GET['dt']);
//try
//{
  if ($dispatchType == 'PAGE')
  {
    print dispatchPage($container);
  }
  else if ($dispatchType == 'BLOCKVIEW')
  {
    print dispatchBlockView($container);
  }
  else if ($dispatchType == 'BLOCK')
  {
    print dispatchBlock($container);
  }
  else if ($dispatchType == 'DOMAIN')
  {
    print dispatchDomain($container);
  }
  else
  {
    header('HTTP/1.1 400 Bad Request');
  }
//}
//catch (Exception $ex)
//{
//  print $ex->getMessage();
//}

//
// DISPATCH FUNCTIONS
//

function dispatchDomain(CoreContainer $c)
{
  $requestMethod = _getRequestMethod();

  $controllerType = $_GET['t'];
  $cls = "Web10\\DomainController\\{$controllerType}Controller";
  $json = $GLOBALS['HTTP_RAW_POST_DATA'];
  $data = json_decode($json);

  $controller = $c->get($cls);
  if (isset($_GET['id']))
  {
    $id = $_GET['id'];
    $obj = $controller->$requestMethod($data, $id);
  }
  else
  {
    $obj = $controller->$requestMethod($data);
  }

  return $obj;
}

function dispatchBlock(CoreContainer $c)
{
  $requestMethod = _getRequestMethod();

  $blockType = $_GET['t'];
  $c['blockType'] = $blockType; //needed to get the right blockrepo
  $type = "Web10\\Web\\Blocks\\$blockType\\Controller";
  $json = $GLOBALS['HTTP_RAW_POST_DATA'];
  $data = json_decode($json);

  $controller = $c->get($type);
  if (isset($_GET['id']))
  {
    $id = $_GET['id'];

    //setup block context
    $bc = $c->get('Web10\Common\Contexts\BlockContext');
    $bc->setupById($id);

    $obj = $controller->$requestMethod($data, $id);
  }
  else
  {
    $obj = $controller->$requestMethod($data);
  }

  return $obj;
}

function dispatchPage(CoreContainer $c)
{
  //setup AccountContext
  //$url = parse_url("http://localhost".$_SERVER['REQUEST_URI'], PHP_URL_PATH);
  $url = '/' . $_GET['u'];
  $p = $c->get('Web10\Common\Contexts\PageContext');
  $p->setupByUrl($url);

  $helper = $c->get('Web10\Web\WebsiteDefHelper');
  $html = $helper->render();
  return $html;
}

function dispatchBlockView(CoreContainer $c)
{
  $blockId = $_GET['id'];
  $blockType = $_GET['t'];

  //setup BlockContext
  $c['blockType'] = $blockType; //needed to get the right blockrepo
  $bc = $c->get('Web10\Common\Contexts\BlockContext');
  $bc->setupById($blockId);

  $type = "Web10\\Web\\Blocks\\$blockType\\Controller";
  $controller = $c->get($type);
  $html = $controller->view(true);
  return $html;
}

function _getRequestMethod()
{
  $action = '';
  $rm = isset($_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE']) ? $_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'] : $_SERVER['REQUEST_METHOD'];
  if ($rm == 'POST') $action = 'create';
  else if ($rm == 'PUT') $action = 'update';
  else if ($rm == 'GET') $action = 'read';
  else if ($rm == 'DELETE') $action = 'delete';
  else throw new BadControllerRequestException('That request type is not valid.');
  //return strtoupper($action);
  return $action;
}
?>
