<?
namespace Web10\Business;

use Web10\Repository\Block\MenuRepo;
use Web10\Common\PageTreeNode;
use Web10\Domain\Blocks\Menu;

class MenuManager
{
  protected $menuRepo;

  public function __construct(MenuRepo $menuRepo)
  {
    $this->menuRepo = $menuRepo;
  }

  public function saveMenu(Menu $m)
  {
    $this->menuRepo->saveAndFlush($m);
  }

  public function getById($id)
  {
    return $this->menuRepo->getById($id);
  }
}
?>
