<?
namespace Web10\Web\Blocks\Menu;

use Web10\Common\Contexts\BlockContext;
use Web10\Common\Contexts\VisitorContext;
use Web10\Business\PageManager;
use Web10\Business\MenuManager;

class Controller
{
  protected $bc;
  protected $vc;

  public function __construct(BlockContext $bc, VisitorContext $vc, PageManager $pm, MenuManager $mm)
  {
    $this->bc = $bc;
    $this->vc = $vc;
    $this->pm = $pm;
    $this->mm = $mm;
  }

  public function view($isPartial=false)
  {
    $view = new View($this->bc, $this->vc, $this->pm, $this->mm);
    return $view->render(!$isPartial);
  }
}
?>
