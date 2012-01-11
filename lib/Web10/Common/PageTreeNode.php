<?
namespace Web10\Common;

use \Domain\Page;

class PageTreeNode
{
  protected $page;
  protected $parent;
  protected $children;

  public function __construct($page)
  {
    $this->page = $page;
    $this->parent = null;
    $this->children = array();
  }

  public function getPage() { return $this->page; }
  public function getChildren() { return $this->children; }
  public function getParent() { return $this->parent; }
  public function addChild(PageTreeNode $n)
  {
    $n->parent = $this;
    $this->children[] = $n;
  }

  public function hasChildren()
  {
    return (count($this->children) > 0);
  }

  public function clearChildren()
  {
    $this->children = array();
  }

  //take a pageTree and cut all the nodes after the depth parameter
  public function trim($depth=1)
  {
    $this->trimR($this, $depth, 0);
  }

  protected function trimR(PageTreeNode $node, $depth, $currentDepth)
  {
    if ($node->hasChildren())
    {
      $currentDepth++;
      foreach ($node->getChildren() as $child)
      {
        if ($currentDepth >= $depth)
        {
          $child->clearChildren();
        }
        else
        {
          $this->trimR($child, $depth, $currentDepth);
        }
      }
    }
  }

  public static function prettyPrint($node, $indent='')
  {
    if ($node->getPage() != null)
    {
      print "<br>" . $indent . $node->getPage()->getName();
    }

    if ($node->getChildren() != null)
    {
      foreach ($node->getChildren() as $n)
      {
        PageTreeNode::prettyPrint($n, $indent."&nbsp;&nbsp;&nbsp;");
      }
    }

    return null;
  }
}
?>
