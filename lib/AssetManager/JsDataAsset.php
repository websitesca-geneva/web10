<?php
namespace AssetManager;

use Web10\Common\JsonEntity;

class JsDataAsset extends Asset
{
  protected $var;
  protected $jsonEntity;
  protected $cls;

  public function __construct($var, JsonEntity $jsonEntity, $cls=null)
  {
    $this->var = $var;
    $this->jsonEntity = $jsonEntity;
    $this->cls = $cls;
  }

  public function getVar() { return $this->var; }
  public function getJsonEntity() { return $this->jsonEntity; }
  public function getCls() { return $this->cls; }

  public function __toString()
  {
    return "JsDataAsset(" . $this->var . ")";
  }
}
?>
