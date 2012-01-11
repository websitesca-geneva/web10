<?php
namespace Web10\DomainController;

use Web10\Business\PageManager;
use Web10\Domain\Page;
use Web10\Domain\Url;
use Web10\Common\Contexts\AccountContext;
use \PDOException;

class PageController
{
  protected $manager;

  public function __construct(PageManager $manager, AccountContext $ac)
  {
    $this->manager = $manager;
    $this->website = $ac->getWebsite();
  }

  public function create($data)
  {
    $p = new Page();
    $p->setName($data->name);
    $p->setLayout($data->layout);
    $p->setWebsite($this->website);
    $p->setTitle("blah blah blah");

    $u = new Url();
    $u->setWebsite($this->website);
    $u->setUrl($data->url);
    $u->setPage($p);

    $p->addUrl($u);

    $this->manager->newPage($p);

    return $p;
  }

  public function update($data, $id)
  {
    try
    {
      $page = $this->manager->savePageAndCommit($id, $data);
      return $page;
    }
    catch (PDOException $ex)
    {
      //$page = $this->manager->getPage($id);
      //return $page;
      return "Error saving the page: " . $ex->getMessage();
    }
  }

  public function delete($data, $id)
  {
    $this->manager->deletePage($id);
  }
}
?>
