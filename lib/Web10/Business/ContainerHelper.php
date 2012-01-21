<?php
namespace Web10\Business;

use Web10\Repository\BlockRepo;

class ContainerHelper
{
  protected $blockRepo;
  
  function __construct(BlockRepo $blockRepo)
  {
    $this->blockRepo = $blockRepo;
  }
}
?>