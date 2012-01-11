<?
require('Web/HttpContext.php');
$context = new Web\HttpContext();
$context->setupCLI();

$em = $context->getEntityManager();

$dql = "select f from Web10\Domain\File f join f.website w where w.id = 1 and f.folder is null";
$query = $em->createQuery($dql);
$rows = $query->getResult();

print "<pre>";
Doctrine\Common\Util\Debug::dump($rows);
print "</pre>";
?>
