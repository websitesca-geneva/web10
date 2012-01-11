<?php
namespace Web10\SAdmin\Controller;

use Web10\SAdmin\WebUtil;

class BaseController
{
  protected $twig;
  protected $util;
  
  public function setWebUtil(WebUtil $util)
  {
  	$this->util = $util;
  }
  
  public function setTemplateEngine($twig)
  {
  	$this->twig = $twig;
  }
  
  function render($tplpath)
  {
  	return $this->twig->render($tplpath, $this->values);
  }
  
  function display($tplpath)
  {
  	print $this->render($tplpath);
  }
  
  //function __construct($datapath)
  function __construct()
  {
  	$this->values = array();
    if (isset($_SESSION['error']))
    {
      //$this->smarty->assign('error', $_SESSION['error']);
      $this->values['error'] = $_SESSION['error'];
      $_SESSION['error'] = null;      
    }
    else if (isset($_SESSION['message']))
    {
      //$this->smarty->assign('message', $_SESSION['message']);
      $this->values['message'] = $_SESSION['message'];
      $_SESSION['message'] = null;
    }
  }
}
?>
