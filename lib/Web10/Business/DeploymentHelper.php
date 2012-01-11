<?php
namespace Web10\Business;

use Web10\Business\ContextHelper;
use Web10\Common\Contexts\AccountContext;
use Web10\Domain\Account;
use Web10\Domain\Website;
use Web10\Domain\Host;
use Web10\Domain\Page;
use Web10\Domain\Url;
use Web10\Repository\WebsiteRepo;
use Web10\Repository\AccountRepo;
use Web10\Repository\HostRepo;
use \InvalidArgumentException;
use \PDOException;

class DeploymentHelper
{
  protected $datapath;
  
  function __construct($datapath, AccountRepo $ar, WebsiteRepo $wr, HostRepo $hr)
  {
    $this->datapath = $datapath;
    //$this->contextHelper = $ch;
    $this->accountRepo = $ar;
    $this->websiteRepo = $wr;
    $this->hostRepo = $hr;
  }
  
  public function setupDataDirectory(Account $a, Website $w, $clearAccount=false, $clearWebsite=false)
  {
    $accountId = $a->getId();
    $websiteId = $w->getId();
    $accountDir = "{$this->datapath}/account_$accountId";
    $websiteDir = "{$this->datapath}/account_$accountId/website_$websiteId";
    
    if ($clearAccount)
      @rename($accountDir, $accountDir."_".uniqid());
    if ($clearWebsite)
      @rename($websiteDir, $websiteDir."_".uniqid());
    
    if (! file_exists($accountDir))
      mkdir($accountDir);
    if (! file_exists($websiteDir))
      mkdir($websiteDir);
    chmod($accountDir, 0777);
    chmod($websiteDir, 0777);
  }
  
  public function setDefaultHost($websiteId, $hostId)
  {
    $website = $this->websiteRepo->getById($websiteId);
    $host = $this->hostRepo->getById($hostId);
    
    $website->setDefaultHost($host);
    
    $this->websiteRepo->saveAndFlush($website);
  }
  
  public function deleteHost($hostId)
  {
    try 
    {
      $host = $this->hostRepo->getById($hostId);
      $this->hostRepo->deleteAndFlush($host);
    }
    catch (PDOException $ex)
    {
      throw new InvalidArgumentException("You cannot delete the default host");
    }
  }
  
  public function addHostToWebsite($websiteId, $hostname)
  {
    try
    {
      $w = $this->getWebsite($websiteId);
      $host = new Host();
      $host->setHostname($hostname);
      $w->addHost($host);
      $host->setWebsite($w);
      $this->websiteRepo->saveAndFlush($w);
    }
    catch (PDOException $ex)
    {
      if ($ex->getCode() == 23000) //duplicate key
        throw new InvalidArgumentException("The hostname $hostname already exists");
      else 
        throw new Exception($ex->getMessage());
    }
  }
  
  public function getWebsite($id)
  {
    return $this->websiteRepo->getById($id);
  }
  
  public function saveWebsite($w)
  {
    $this->websiteRepo->saveAndFlush($w);
  }
  
  public function getAccount($id)
  {
    return $this->accountRepo->getById($id);
  }
  
  public function saveAccount($account)
  {
    $this->accountRepo->saveAndFlush($account);
  }
  
  public function accountExists($email)
  {
    $account = $this->accountRepo->getAccountByEmail($email);
    return (! empty($account));
  }
  
  public function createAccount($email, $hid, $websiteDef)
  {
    if ($this->accountExists($email))
    {
      throw new InvalidArgumentException("An account with email $email already exists.");
    }
    
    //ACCOUNT
    $account = new Account();
    $account->setEmail($email);
    $account->setPassword($email);
    
    //WEBSITE
    $website = new Website();
    $website->setAccount($account);
    $website->setWebsiteDef($websiteDef);
    $account->addWebsite($website);
    
    //HOST
    $host = new Host();
    $host->setHostname("$hid.websites.ca");
    $host->setWebsite($website);
    $website->addHost($host);
    $website->setDefaultHost($host);
    
    //PAGE
    $page = new Page();
    $page->setName('Home');
    $page->setTitle('Home');
    $page->setLayout('main');
    $page->setWebsite($website);
    $website->addPage($page);
    
    //URL
    $url = new Url();
    $url->setUrl('/');
    $url->setWebsite($website);
    $url->setPage($page);
    $page->addUrl($url);
    
    $this->websiteRepo->saveAndFlush($website);
    
    //$this->ac = new AccountContext($this->contextHelper);
    //$this->ac->setupByWebsite($website);
    
    $this->setupDataDirectory($account, $website);
    
    return $account;
  }
}
?>
