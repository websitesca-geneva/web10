<?php
namespace Web10\DomainController;

use Web10\Common\JsonCollection;
use Web10\Business\PageManager;
use Web10\Domain\Page;
use Web10\Domain\Url;
use Web10\Common\Contexts\AccountContext;

class PageListController
{
  protected $manager;

  public function __construct(PageManager $manager, AccountContext $ac)
  {
    $this->manager = $manager;
    $this->website = $ac->getWebsite();
  }

  public function update($data)
  {
    $collection = new JsonCollection();
    try
    {
      $this->manager->beginTransaction();
      foreach ($data as $i=>$p)
      {
        $id = $p->id;
        $parentId = $p->parentPageId;
        $ordering = $p->ordering;

        $page = $this->manager->getPage($id);
        $page->setOrdering($ordering);
        $oldParent = $page->getParent();
        if ($oldParent)
        {
          $oldParent->removeSubpage($page);
          $this->manager->savePage($oldParent);
        }

        if ($parentId > 0)
        {
          $newParent = $this->manager->getPage($parentId);
          $page->setParent($newParent);
          $newParent->addSubpage($page);
          $this->manager->savePage($newParent);
        }
        else $page->setParent(null);

        $this->manager->savePage($page);

        $collection->add($page);
      }
      $this->manager->commitTransaction();
      $this->manager->flush();
    }
    catch (\Exception $ex)
    {
      $this->manager->rollbackTransaction();
    }
    return $collection;
  }
}
?>
