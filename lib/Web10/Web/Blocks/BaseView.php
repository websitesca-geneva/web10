<?php
namespace Web10\Web\Blocks;

use Web10\Common\Contexts\BlockContext;
use Web10\Common\Contexts\VisitorContext;

abstract class BaseView
{
  protected $block;
  protected $visitor;

  public function __construct(BlockContext $bc, VisitorContext $vc)
  {
    $this->block = $bc->getBlock();
    $this->visitor = $vc->getVisitor();
  }

  public function render($wrap=true)
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

    $html .= "<div class='block $blockType' blockid='$blockId'>";
    $html .= $this->getHTML($this->block);
    $html .= "</div>";

    if ($wrap)
    {
      $html .= "</div>";
    }

    return $html;
  }
}
?>
