<?php
namespace Web10\DomainController;

abstract class BaseController
{
  public function __construct()
  {

  }

  public function create($data) {}
  public function update($id, $data) {}
  public function read($id) {}
  public function delete($id) {}
}
?>
