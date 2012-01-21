<?
namespace Web10\Web\Blocks\Container;

use Web10\Domain\Blocks\Container;
use Web10\Common\Contexts\BlockContext;
use Web10\Common\Contexts\VisitorContext;
use \InvalidArgumentException;

class Controller
{
  protected $bc;
  protected $vc;
  protected $manager;
  protected $visitor;

  public function __construct(BlockContext $bc, VisitorContext $vc)
  {
    $this->bc = $bc;
    $this->vc = $vc;
    $this->block = $bc->getBlock();
    $this->visitor = $vc->getVisitor();
  }
  
  protected function render($wrap=true)
  { 
    $blockType = $this->block->getBlockType();//$this->get_class_name($this->block);
    $blockId = $this->block->getId();
    $html = "";

    if ($wrap)
    {
      $html .= "<div class='block-wrapper'>";
      if ($this->visitor->getIsAuthenticated())
      {
        $html .= "<div class='block-menu'><a href='javascript:void(0);' name='edit'>Edit</a></div>";
      }
    }

    $html .= "<div class='block Container' blockid='$blockId'>";
    $html .= $this->getHTML($this->block);
    $html .= "</div>";

    if ($wrap)
    {
      $html .= "</div>";
    }

    return $html;
  }
  
  protected function getHTML(Container $container)
  {
    $blocks = $container->getBlocks();
    if (count($blocks) == 0) return "EMPTY";
    else 
    {
      return "LISTING BLOCKS";
    }
  }

  public function view($isPartial=false)
  {
    return $this->render(!$isPartial);
  }
}
?>
