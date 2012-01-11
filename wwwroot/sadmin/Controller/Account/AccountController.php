<?php
namespace Web10\SAdmin\Controller\Account;

use Web10\Business\DeploymentHelper;
use Web10\Business\WebsiteDefManager;
use Web10\SAdmin\Controller\BaseController;
use \InvalidArgumentException;
use Smarty;

class AccountController extends BaseController
{
  protected $helper;
  
  function __construct(DeploymentHelper $helper, WebsiteDefManager $defManager)
  {
  	parent::__construct();
    $this->helper = $helper;
    $this->defManager = $defManager;
  }
  
  function edit()
  {
    $id = $_REQUEST['id'];
    $account = $this->helper->getAccount($id);
    $this->values['account'] = $account;
    $this->values['title'] = 'Edit Account';
    $this->display('Controller/Account/edit.tpl');
  }
  
  function edit2()
  {
    $id = $_REQUEST['id'];
    $account = $this->helper->getAccount($id);
    $account->setEmail($_REQUEST['email']);
    $account->setPassword($_REQUEST['password']);
    $this->helper->saveAccount($account);
    
    $this->util->redirect("/sadmin/Account/edit?id=$id", 'Saved!');
  }
  
  function add()
  {
    $this->displayAdd();
  }
  
  function displayAdd($email=null, $hid=null, $websiteDef=null)
  {
  	$this->values['email'] = $email;
  	$this->values['hid'] = $hid;
  	$this->values['websiteDef'] = $websiteDef;
  	
    $defs = $this->defManager->getWebsiteDefs();
    array_unshift($defs, '');
    $this->values['websiteDefs'] = $defs;
    $this->values['title'] = 'Add Account';
    $this->display('Controller/Account/add.tpl');
  }
  
  function add2()
  {
    $email = $_REQUEST['email'];
    $hid = $_REQUEST['hid'];
    $websiteDef = $_REQUEST['websiteDef'];
    
    if (empty($email))
    {
      $this->values['error'] = 'You must complete all fields.';
      $this->displayAdd($email, $hid, $websiteDef);
    }
    else
    {
      try 
      {
        $account = $this->helper->createAccount($email, $hid, $websiteDef);
        $this->util->redirect("/sadmin/Account/edit?id={$account->getId()}", 'Added!');
      }
      catch (InvalidArgumentException $ex)
      {
        $this->values['error'] = $ex->getMessage();
        $this->displayAdd($email, $hid, $websiteDef);  
      }
    }
  }
}
?>
