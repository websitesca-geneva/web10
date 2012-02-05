<?
namespace Web10\Repository\Block;

use \InvalidArgumentException;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\NoResultException;
use Web10\Domain\Website;
use Web10\Domain\Page;
use Web10\Domain\Blocks\Container;
use Web10\Repository\DomainEntityRepo;

class BlockRepo extends DomainEntityRepo
{
  protected $blockType;

  public function __construct(EntityManager $em, $blockType='Web10\Domain\Blocks\Block')
  {
    $this->blockType = $blockType;
    parent::__construct($em, new ClassMetadata($blockType));
  }

  public function getAllByPage(Page $p)
  {
    $dql = "select b from Web10\Domain\Blocks\Block b join b.page p where p.id = :page_id";
    $query = $this->_em->createQuery($dql);
    $query->setParameter('page_id', $p->getId());
    return $query->getResult();
  }

  public function disassociateBlocksFromPage($pageId)
  {
    $dql = "update Web10\Domain\Blocks\Block b set b.scope = null where
      b.scope = 'PAGE' and 
      b.id IN (select b2.id from Web10\Domain\Blocks\Block b2 join b2.page p where p.id = :page_id)";
    $query = $this->_em->createQuery($dql);
    $query->setParameter('page_id', $pageId);
    $query->execute();
  }

  protected function getLayoutScopedBlock(Website $website, $layout, $name)
  {
    $query = $this->_em->createQuery("select b from {$this->blockType} b
      join b.website w 
      where w.id = :website_id and b.layout = :layout and b.scope = 'LAYOUT' and b.name = :name");
    $query->setParameter('website_id', $website->getId());
    $query->setParameter('layout', $layout);
    $query->setParameter('name', $name);
    return $query->getSingleResult();
  }

  protected function getPageScopedBlock(Website $website, Page $page, $name)
  {
    $query = $this->_em->createQuery("select b from {$this->blockType} b
      join b.website w join b.page p 
      where w.id = :website_id and p.id = :page_id and b.scope = 'PAGE' and b.name = :name");
    $query->setParameter('website_id', $website->getId());
    $query->setParameter('page_id', $page->getId());
    $query->setParameter('name', $name);
    return $query->getSingleResult();
  }

  protected function getContainerScopedBlock(Website $website, Container $container, $name)
  {
    $query = $this->_em->createQuery("select b from {$this->blockType} b
      join b.website w join b.container c 
      where w.id = :website_id and c.id = :container_id and b.scope = 'CONTAINER' and b.name = :name");
    $query->setParameter('website_id', $website->getId());
    $query->setParameter('container_id', $container->getId());
    $query->setParameter('name', $name);
    return $query->getSingleResult();
  }

  protected function installBlock(Website $website, $layout=null, Page $page=null, $scope, $name, Container $container=null, $extraBlockProperties=array())
  {
    $b = new $this->blockType();
    $b->setWebsite($website);
    if ($scope == 'PAGE')
      $b->setPage($page);
    else if ($scope == 'LAYOUT')
      $b->setLayout($layout);
    else if ($scope == 'CONTAINER')
      $b->setContainer($container);
    $b->setScope($scope);
    $b->setName($name);

    foreach ($extraBlockProperties as $name=>$value)
    {
      $first = substr($name, 0, 1);
      $method = 'set' . strtoupper($first) . substr($name, 1);
      if (method_exists($b, $method))
      	$b->$method($value);
      else
      	throw new InvalidArgumentException("The method $method does not exist for property $name with blockType {$this->blockType}");
    }

    $this->_em->persist($b);
    $this->_em->flush();
    
    if (method_exists($b, 'onInstall'))
    {
    	$b->onInstall();
    }
    
    return $b;
  }

  public function getBlock(Website $website, $layout, $page, $scope, $name, $container=null, $extraBlockProperties=array())
  {
    $block = null;
    try
    {
      if ($scope == 'LAYOUT')
      {
        $block = $this->getLayoutScopedBlock($website, $layout, $name);
      }
      else if ($scope == 'CONTAINER')
      {
        $block = $this->getContainerScopedBlock($website, $container, $name);
      }
      else
      {
        $block = $this->getPageScopedBlock($website, $page, $name);
      }
    }
    catch (NoResultException $ex)
    {
      $block = $this->installBlock($website, $layout, $page, $scope, $name, $container, $extraBlockProperties);
    }

    if (empty($block))
    {
      throw new Exception("Could not get nor install block: " . $ex->getMessage());
    }
    return $block;
  }

  public function getBlockById(Website $website, $id)
  {
    $query = $this->_em->createQuery("select b from {$this->blockType} b join b.website w
      where w.id = :website_id and b.id = :id");
    $query->setParameter('id', $id);
    $query->setParameter('website_id', $website->getId());
    return $query->getSingleResult();
  }
}
?>
