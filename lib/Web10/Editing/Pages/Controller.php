<?
namespace Web10\Editing\Pages;

use Web10\Business\PageManager;
use Web10\Repository\PageRepo;
use Web10\Repository\LayoutRepo;
use Web10\Repository\UrlRepo;
use Web10\Repository\BlockRepo;
use Web10\Web\BaseController;
use Web10\Web\ControllerResponseOK;
use Web10\Web\ControllerResponseError;

class Controller extends BaseController
{
  protected $pageManager;

  public function __construct(PageManager $pageManager)
  {
    parent::__construct();
    $this->pageManager = $pageManager;
  }

  public function pageManagerDialog()
  {
    return new ControllerResponseOK(new PageManagerDialog($this->pageManager));
  }

  public function editPageTab($pageId)
  {
    return new ControllerResponseOK(new EditPageTab($this->pageManager, $pageId));
  }

  public function savePage($pageId, $params)
  {
    $this->pageManager->savePage($pageId, $params);
    return new ControllerResponseOK(new PageManagerDialog($this->pageManager), "Page saved!");
  }

  public function addPage($name, $title, $layoutId, $url)
  {
    $this->pageManager->addPage($name, $title, $layoutId, "/$url");
    return new ControllerResponseOK(new PageManagerDialog($this->pageManager), "Page added!");
  }

  public function deletePage($pageId)
  {
    $this->pageManager->deletePage($pageId);
    return new ControllerResponseOK(new PageManagerDialog($this->pageManager), "Page deleted!");
  }

  public function savePages($ordering)
  {
    preg_match_all("/page\[(\d+)\]=(root|(\d+))/u", $ordering, $matches);
    $map = array();
    for ($i=0; $i<count($matches[1]); $i++)
    {
      $id = $matches[1][$i];
      $pid = ($matches[2][$i]=='root') ? null : $matches[2][$i];
      $map[$id] = $pid;
    }

    $this->pageManager->updateOrdering($map);
    return new ControllerResponseOK(null, "Pages saved!");
  }
}
?>
