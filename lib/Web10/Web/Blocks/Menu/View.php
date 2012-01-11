<?
namespace Web10\Web\Blocks\Menu;

use Web10\Domain\Blocks\Menu;
use Web10\Web\Blocks\BaseView;
use Web10\Web\WebHelper;
use Web10\Business\PageManager;
use Web10\Business\MenuManager;
use Web10\Common\Contexts\BlockContext;
use Web10\Common\Contexts\VisitorContext;

class View extends BaseView
{
  public function __construct(BlockContext $bc, VisitorContext $vc, PageManager $pm, MenuManager $mm)
  {
    parent::__construct($bc, $vc);
    $this->pm = $pm;
    $this->mm = $mm;
  }

  public function getHTML($menu)
  {
    $helper = new WebHelper();
    $pageTreeRoot = $this->pm->buildPageTree();
    $pageTreeRoot->trim($menu->getPageHierarchyDepth());
    $this->menu2Html($pageTreeRoot, $html, $helper);
    return $html;
  }

  protected function menu2Html($pageTreeNode, &$html, $helper)
  {
    if ($pageTreeNode->getPage() != null)
    {
      $page = $pageTreeNode->getPage();
      $link = $helper->getRelativeLink($page);
      $name = $page->getName();
      $html .= "<a href='$link'>$name</a>";
    }
    if ($pageTreeNode->hasChildren())
    {
      $html .= "\n<ul>";
      foreach ($pageTreeNode->getChildren() as $childNode)
      {
        $id = $childNode->getPage()->getId();
        $html .= "\n<li>";
        $this->menu2Html($childNode, $html, $helper);
        $html .= "</li>";
      }
      $html .= "\n</ul>";
    }
  }
}
?>
