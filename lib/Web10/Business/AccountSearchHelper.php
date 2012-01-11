<?php
namespace Web10\Business;

use Web10\Repository\AccountRepo;
use Web10\Repository\HostRepo;

class AccountSearchHelper
{
  protected $accountRepo;
  
  function __construct(AccountRepo $ar)
  {
    $this->accountRepo = $ar;
  }
  
  function findAccountsByEmailStartingWith($str)
  {
    return $this->accountRepo->findAccountsByEmailStartingWith($str);
  }
  
  function findAccountsByHostStartingWith($str)
  {
    return $this->accountRepo->findAccountsByHostStartingWith($str);
  }
}
?>
