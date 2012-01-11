<?php
namespace Web10\Common\Contexts;

use Web10\Business\ContextHelper;

class VisitorContext
{
  protected $helper;
  protected $visitor;

  public function __construct(ContextHelper $helper)
  {
    $this->helper = $helper;
  }

  public function getVisitor() { return $this->visitor; }

  public function setupBySessionId($sessionId)
  {
    $this->visitor = $this->helper->getVisitor($sessionId);
  }
}
?>
