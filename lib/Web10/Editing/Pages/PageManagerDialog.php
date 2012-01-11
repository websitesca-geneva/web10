<?
namespace Web10\Editing\Pages;

use Web10\Web\DialogTabbed;
use Web10\Business\PageManager;

class PageManagerDialog extends DialogTabbed
{
  protected $pageManager;

  public function __construct(PageManager $pageManager)
  {
    $params = array();
    parent::__construct("Manage Pages");
    $this->pageManager = $pageManager;

    $this->addDialogTab(new PageListTab($this->pageManager));
    $this->addDialogTab(new AddPageTab($this->pageManager));
  }
}
?>
