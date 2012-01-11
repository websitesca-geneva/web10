<?
namespace Web10\Editing\Pages;

use Web10\Business\PageManager;
use Web10\Web\FormHelper;
use Web10\Web\DialogTab;

class PageListTab extends DialogTab
{
  protected $pageManager;

  public function __construct(PageManager $pageManager)
  {
    $this->pageManager = $pageManager;
    parent::__construct('Pages List');
  }

  public function getBody()
  {
    $html = "Click and drag the pages below to reorder and change the hierarchy.";
    $html .= "<div class='pagesList'>";
    $root = $this->pageManager->buildPageTree(); //tree is of type PageTreeNode
    $html .= "<p>\n\n";
    $this->tree2Html($root, $html);
    $html .= "\n\n";
    $html .= "</div>";
    $html .= "<p><a href='javascript:void(0);' onclick='javascript:PageManager_savePagesClick(this);'>Save Pages</a></p>";
    return $html;
  }

  protected function tree2Html($pageTreeNode, &$html)
  {
    if ($pageTreeNode->getPage() != null)
    {
      $pageId = $pageTreeNode->getPage()->getId();
      $html .= "<div class='page'>";
      $html .= $pageTreeNode->getPage()->getName();
      $html .= " / <a href='javascript:void(0);' onclick=\"PageManager_editPageTab(this, '$pageId');\">Edit</a>";
      $html .= "</div>";
    }
    if ($pageTreeNode->hasChildren())
    {
      $cls = "";
      if ($pageTreeNode->getPage() == null) //only put class if root
      $cls = "class='sortable'";
      $html .= "\n<ol $cls>";
      foreach ($pageTreeNode->getChildren() as $childNode)
      {
        $id = $childNode->getPage()->getId();
        $html .= "\n<li id='page_$id'>";
        $this->tree2Html($childNode, $html);
        $html .= "\n</li>";
      }
      $html .= "\n</ol>";
    }
  }
}
