<?php
namespace Web10\Common;

interface Transactionable
{
  public function beginTransaction();
  public function commitTransaction();
  public function rollbackTransaction();
  public function flush();
}
?>
