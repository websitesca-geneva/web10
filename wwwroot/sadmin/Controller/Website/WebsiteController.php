<?php
namespace Web10\SAdmin\Controller\Website;

use Web10\Business\DeploymentHelper;
use Web10\Business\WebsiteDefManager;
use Web10\SAdmin\Controller\BaseController;
use \InvalidArgumentException;
use Smarty;

class WebsiteController extends BaseController
{
  protected $helper;
  protected $defManager;
  
  function __construct(DeploymentHelper $helper, WebsiteDefManager $defManager)
  {
  	parent::__construct();
    $this->helper = $helper;
    $this->defManager = $defManager;
  }
  
  function addHost()
  {
    $id = $_REQUEST['id'];
    $hostname = $_REQUEST['hostname'];
    
    try 
    {
      $this->helper->addHostToWebsite($id, $hostname);
      $this->util->redirect("/sadmin/Website/edit?id=$id", "Added!");
    } 
    catch (InvalidArgumentException $ex) 
    {  
      $this->util->redirect("/sadmin/Website/edit?id=$id", $ex->getMessage(), true);
    }
    
  }
  
  public function setDefaultHost()
  {
    $websiteId = $_REQUEST['website_id'];
    $hostId = $_REQUEST['host_id'];
    $this->helper->setDefaultHost($websiteId, $hostId);
    $this->util->redirect("/sadmin/Website/edit?id=$websiteId", "Default host updated.");
  }
  
  public function deleteHost()
  {
    $hostId = $_REQUEST['host_id'];
    $websiteId = $_REQUEST['website_id'];
    try
    {
      $this->helper->deleteHost($hostId);
      $this->util->redirect("/sadmin/Website/edit?id=$websiteId", "Host deleted.");
    }
    catch (InvalidArgumentException $ex)
    {
      $this->util->redirect("/sadmin/Website/edit?id=$websiteId", $ex->getMessage(), true);
    }
  }
  
  public function edit() 
  {
    $this->displayEdit($_REQUEST['id']);
  }
  
  protected function displayEdit($id)
  {
    $w = $this->helper->getWebsite($id);
    $defs = $this->defManager->getWebsiteDefs();
    array_unshift($defs, '');
    $this->values['website'] = $w;
    $this->values['websiteDefs'] = $defs;
    $this->values['title'] = 'Edit Website';
    $this->display('Controller/Website/edit.tpl');
  }
  
  public function edit2()
  {
    $id = $_REQUEST['id'];
    $websiteDef = $_REQUEST['websiteDef'];
    $w = $this->helper->getWebsite($id);
    $w->setWebsiteDef($websiteDef);
    $this->helper->saveWebsite($w);
	$this->util->redirect("/sadmin/Website/edit?id=$id", 'Saved!');
  }
}
?>
