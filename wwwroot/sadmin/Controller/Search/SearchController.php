<?php
namespace Web10\SAdmin\Controller\Search;

use Web10\SAdmin\Controller\BaseController;
use Web10\Business\AccountSearchHelper;
use \InvalidArgumentException;
use Smarty;

class SearchController extends BaseController
{
  protected $helper;
  
  function __construct(AccountSearchHelper $am)
  {
  	parent::__construct();
    $this->helper = $am;
  }
  
  function search()
  {
    $query = $_REQUEST['query'];
    $accounts = null;
    if (!empty($query))
    {
      $accounts = $this->helper->findAccountsByEmailStartingWith($query);
      $accounts = array_merge($accounts, $this->helper->findAccountsByHostStartingWith($query));
    }
    
    //$this->smarty->assign('accounts', $accounts);
    //$this->smarty->display('Controller/Search/search.tpl');
    $this->values['accounts'] = $accounts;
    $this->values['title'] = 'Search Results';
    $this->display('Controller/Search/search.tpl');
  }
}
?>
