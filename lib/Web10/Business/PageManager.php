<?
namespace Web10\Business;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NoResultException;
use Web10\Repository\pageRepo;
use Web10\Repository\LayoutRepo;
use Web10\Repository\UrlRepo;
use Web10\Repository\Block\BlockRepo;
use Web10\Domain\Website;
use Web10\Domain\Page;
use Web10\Domain\Url;
use Web10\Common\PageTreeNode;
use Web10\Domain\Layout;
use \InvalidArgumentException;
use \Exception;
use Web10\Common\Transactionable;
use Web10\Common\Contexts\AccountContext;

class PageManager implements Transactionable
{
  protected $em;
  protected $pageRepo;
  protected $layoutRepo;
  protected $urlRepo;
  protected $blockRepo;

  public function __construct(EntityManager $em, AccountContext $ac)
  {
    $this->em = $em;
    $this->website = $ac->getWebsite();
  }

  public function beginTransaction() { return $this->em->beginTransaction(); }
  public function commitTransaction() { return $this->em->commit(); }
  public function rollbackTransaction() { return $this->em->rollback(); }
  public function flush() { $this->em->flush(); }

  public function setPageRepo(PageRepo $pageRepo) { $this->pageRepo = $pageRepo; }
  public function setLayoutRepo(LayoutRepo $layoutRepo) { $this->layoutRepo = $layoutRepo;}
  public function setUrlRepo(UrlRepo $urlRepo) { $this->urlRepo = $urlRepo; }
  public function setBlockRepo(BlockRepo $blockRepo) { $this->blockRepo = $blockRepo; }

  public function getDefaultHost()
  {
    return $this->website->getDefaultHost()->getHostname();
  }

  public function getDefaultUrl()
  {
    throw new Exception('not implemented yet');
  }

  public function getLayout($id)
  {
    return $this->layoutRepo->getById($id);
  }

  public function getAllLayouts()
  {
    return $this->layoutRepo->getAllByWebsiteDef($this->website->getWebsiteDef());
  }

  public function getPage($pageId)
  {
    return $this->pageRepo->getById($pageId);
  }

  public function getAll()
  {
    return $this->pageRepo->getAllOrdered($this->website);
  }

  public function pageHasChildren($pageId)
  {
    return $this->pageRepo->hasSubpages($pageId);
  }

  public function deletePage($pageId)
  {
    if ($this->pageRepo->hasSubpages($pageId))
    {
      throw new InvalidArgumentException("You cannot delete this page because it has subpages.");
    }

    $conn = $this->em->getConnection();
    try
    {
      //$this->em->beginTransaction();
      $conn->beginTransaction();
      $page = $this->pageRepo->getById($pageId);
      foreach ($this->blockRepo->getAllByPage($page) as $block)
      {
        $block->setScope(null);
        $block->setPage(null);
        $this->blockRepo->save($block);
      }
      foreach ($this->urlRepo->getAllByPage($page) as $url)
      {
        $this->urlRepo->delete($url);
      }
      $this->pageRepo->delete($page);
      //$this->em->commit();
      $conn->commit();
      $this->em->flush();
    }
    catch (Exception $ex)
    {
      //$this->em->rollback();
      $conn->rollback();
      throw $ex;
      //throw new \Exception("Cannot remove page: " . $ex->getTraceAsString());
    }
  }

  public function newPage(Page $p)
  {
    $this->pageRepo->saveAndFlush($p);
    return $p;
  }

  public function savePage(Page $p)
  {
    $this->pageRepo->save($p);
  }

  public function savePageAndCommit($pageId, $dataObj)
  {
    $page = $this->pageRepo->getById($pageId);
    $page->setName($dataObj->name);
    $page->setTitle($dataObj->title);
    $page->setLayout($dataObj->layout);

    //check if layout changed
    //if ($dataObj->layoutId != $page->getLayout()->getId())
    //{
    //  $layout = $this->layoutRepo->getLayoutById($dataObj->layoutId);
    //  $page->setLayout($layout);
    //}

    //if the url already exists, repoint it at this new page
    $url = $this->urlRepo->getUrl($this->website, $dataObj->url);
    if ($url)
    {
    	$url->setPage($page);
    }
    else 
    {
    	$url = new Url();
    	$url->setPage($page);
    	$url->setUrl($dataObj->url);
    	$url->setWebsite($this->website);
    }

    $page->addUrl($url);

    $this->urlRepo->save($url);
    $this->pageRepo->save($page);
    $this->em->flush();

    return $page;
  }

  public function addPage($name, $title, $layout, $url)
  {
    $p = new Page();
    $p->setName($name);
    $p->setTitle($title);
    $p->setWebsite($this->website);
    $website->addPage($p);
    $p->setLayout($layout);

    $u = new Url();
    $u->setWebsite($this->website);
    $u->setPage($p);
    $u->setUrl($url);

    $p->addUrl($u);

    $this->pageRepo->saveAndFlush($p);
  }

  public function updateOrdering($map)
  {
    //first clear all parent/child associations
    $idhash = array();
    foreach ($map as $pageId=>$parentId)
    {
      if (!isset($idhash[$pageId]))
      {
        $page = $this->pageRepo->getById($pageId);
        if ($page == null)
        throw new \Exception("tsp: $pageId");
        $idhash[$pageId] = $page;
        $page->setParent(null);
        $page->clearSubpages();
      }
      if (!empty($parentId))
      {
        if (!isset($idhash[$parentId]))
        {
        $parent = $this->pageRepo->getById($parentId);
        $idhash[$parentId] = $parent;
        $parent->setParent(null);
        $parent->clearSubpages();
        }
      }
    }
    
    //now rebuild the parent/child associations
    $count = 1;
    foreach ($map as $pageId=>$parentId)
    {
    $page = $idhash[$pageId];
    $parent = null;
      if (!empty($parentId))
      $parent = $idhash[$parentId];

      $page->setOrdering($count);
      if ($parent)
      {
      $page->setParent($parent);
      $parent->addSubpage($page);
      }
      $count++;
      
      $this->pageRepo->save($page);
    }
    $this->em->flush();
  }

  private function findPageInTree($pageTreeNode, $id)
  {
    if ($pageTreeNode->getPage() != null)
    {
      if ($pageTreeNode->getPage()->getId() == $id)
      {
        return $pageTreeNode;
      }
    }
    
    if ($pageTreeNode->getChildren() != null)
    {
      foreach ($pageTreeNode->getChildren() as $node)
      {
      $found = $this->findPageInTree($node, $id);
        if ($found != null)
        {
          return $found;
        }
      }
    }
    
    return null;
  }

  public function buildPageTree()
  {
  $pageTree = array();
  $root = new PageTreeNode(null);
  $pages = $this->pageRepo->getAllOrdered($this->website);

    foreach ($pages as $page)
    {
    $id = $page->getId();
      if ($page->getParent() == null)
      {
      $n = new PageTreeNode($page);
      $root->addChild($n);
      }
      else
      {
      $node = $this->findPageInTree($root, $page->getParent()->getId());
        if ($node != null)
        {
        $n = new PageTreeNode($page);
        $node->addChild($n);
        }
      }
    }
    return $root;
  }
}
?>
