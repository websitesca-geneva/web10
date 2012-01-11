<?php
namespace Web10\Common\Contexts;

use Web10\Business\ContextHelper;

class PageContext
{
  protected $helper;
  protected $page;
  protected $website;

  public function __construct(ContextHelper $helper, AccountContext $ac)
  {
    $this->helper = $helper;
    $this->website = $ac->getWebsite();
  }

  public function getPage() { return $this->page; }

  public function setupByUrl($url)
  {
    $this->page = $this->helper->getPageByUrl($this->website, $url);
  }
}
?>
