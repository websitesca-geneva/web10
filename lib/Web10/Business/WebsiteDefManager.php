<?php
namespace Web10\Business;

use Web10\Common\Contexts\AccountContext;
use Web10\Domain\Website;
use Web10\Domain\Page;

class WebsiteDefManager
{
  protected $datapath;
  protected $rootpathweb;
  protected $ac;
  
  function __construct($datapath, $rootpathweb)
  {
    $this->datapath = $datapath;
    $this->rootpathweb = $rootpathweb;
  }
  
  public function getLayoutPath(Page $page)
  {
    $defPath = $this->getDefPath($page->getWebsite());
    $layoutPath = "$defPath/{$page->getLayout()}.xhtml";
    return $layoutPath;
  }
  
  public function getDefPath(Website $website)
  {
    $def = $website->getWebsiteDef();
    $websitePathDir = $this->datapath . "/account_{$website->getAccount()->getId()}/website_{$website->getId()}/def";
    $accountPathDir = $this->datapath . "/account_{$website->getAccount()->getId()}/def";
    $defPathDir = $this->rootpathweb . "/defs/$def";
    if (empty($def))
    {
      if (is_dir($websitePathDir))
        return $websitePathDir;
      else 
        return $accountPathDir;
    }
    else 
    {
      return $defPathDir;
    }
  }
  
  public function getLayouts(Website $website)
  {
    $dir = $this->getDefPath($website);
    
    $layouts = array();
    if (is_dir($dir)) 
    {
      if ($dh = opendir($dir)) 
      {
        while (($file = readdir($dh)) !== false) 
        {
          if ($file == ".") continue;
          if ($file == "..") continue;
          if (filetype($dir.'/'.$file) == 'file')
          {
            if (substr($file, -6) == '.xhtml')
            {
              $name = substr($file, 0, strlen($file)-6);//cut off the .xhtml
              if ($name == 'main')
              	array_unshift($layouts, $name);
              else
              	$layouts[] = $name;
            }
          }
        }
        closedir($dh);
      }
    }
    return $layouts;
  }
  
  public function getWebsiteDefs()
  {
    $dir = "{$this->rootpathweb}/defs"; 
    $defs = array();
    if (is_dir($dir)) 
    {
      if ($dh = opendir($dir)) 
      {
        while (($file = readdir($dh)) !== false) 
        {
          if ($file == ".") continue;
          if ($file == "..") continue;
          if (filetype($dir.'/'.$file) == 'dir')
          {
            $defs[] = $file;
          }
        }
        closedir($dh);
      }
    }
    return $defs;
  }
}
?>
