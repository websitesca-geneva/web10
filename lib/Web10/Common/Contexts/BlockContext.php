<?php
namespace Web10\Common\Contexts;

use Web10\Business\ContextHelper;
use Web10\Domain\Blocks\Block;

class BlockContext
{
  protected $helper;
  protected $block;
  protected $page;

  public function __construct(ContextHelper $helper, PageContext $pc)
  {
    $this->helper = $helper;
    $this->page = $pc->getPage();
  }

  public function getBlock() { return $this->block; }

  public function setupById($blockId)
  {
    $this->block = $this->helper->getBlockById($blockId);
  }

  public function setupByBlock(Block $block)
  {
    $this->block = $block;
  }
}
?>
