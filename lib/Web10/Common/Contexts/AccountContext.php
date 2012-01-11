<?php
namespace Web10\Common\Contexts;

use Web10\Business\ContextHelper;

class AccountContext
{
  protected $helper;
  protected $website;

  public function __construct(ContextHelper $helper)
  {
    $this->helper = $helper;
  }

  public function getWebsite() { return $this->website; }
  public function getAccount() { return $this->website->getAccount(); }

  public function setupByHostname($hostname)
  {
    $this->website = $this->helper->getWebsiteByHostname($hostname);
  }

  public function setupByWebsite($website)
  {
    $this->website = $website;
  }
}
?>
