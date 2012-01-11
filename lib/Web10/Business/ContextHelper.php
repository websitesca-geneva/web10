<?php
namespace Web10\Business;

use \Exception;
use Web10\Domain\Page;
use Web10\Domain\Visitor;
use Web10\Repository\UrlRepo;
use Web10\Repository\HostRepo;
use Web10\Repository\VisitorRepo;
use Web10\Repository\Block\BlockRepo;

class ContextHelper
{
  protected $hostRepo;
  protected $urlRepo;
  protected $blockRepo;
  protected $visitorRepo;

  public function ContextHelper() {}

  public function setHostRepo(HostRepo $hostRepo) { $this->hostRepo = $hostRepo; }
  public function setUrlRepo(UrlRepo $urlRepo) { $this->urlRepo = $urlRepo; }
  public function setBlockRepo(BlockRepo $blockRepo) { $this->blockRepo = $blockRepo; }
  public function setVisitorRepo(VisitorRepo $visitorRepo) { $this->visitorRepo = $visitorRepo; }

  public function getBlockById($blockId)
  {
    return $this->blockRepo->getById($blockId);
  }

  public function getBlockByName(Page $page, $blockType, $blockName, $blockScope, $blockContainer, $blockParams)
  {
    //$page = $pageContext->getPage();
    //$inj = InjectorWrapper::getStatic();
    //$pc = $inj->get('Web10\Common\Contexts\PageContext');
    //$page = $pc->getPage();
    //$this->blockRepo = $inj->get('Web10\Repository\Block\BlockRepo', array('blockType'=>"Web10\\Domain\\Blocks\\{$blockType}"));
    return $this->blockRepo->getBlock($page->getWebsite(),
    $page->getLayout(), $page, $blockScope, $blockName, $blockContainer, $blockParams);
  }

  public function getVisitor($sessionId)
  {
    return $this->visitorRepo->getBySessionId($sessionId);
  }

  public function getWebsiteByHostname($hostname)
  {
    $host = $this->hostRepo->getHost($hostname);
    if ($host == null)
    {
      throw new Exception("Hostname $hostname does not exist.");
    }
    return $host->getWebsite();
  }

  public function getPageByUrl($website, $url)
  {
    $url = $this->urlRepo->getUrl($website, $url);
    return $url->getPage();
  }
}
?>
