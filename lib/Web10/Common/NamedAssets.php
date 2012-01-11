<?php
namespace Web10\Common;

use AssetManager\AssetManager;
use AssetManager\JsAsset;

class NamedAssets extends AssetManager
{
  protected $names;
  
  function __construct()
  {
    $this->names = array();
    $this->setup();
  }
  
  protected function setup() 
  {
    $this->add('jquery', array(
      JsAsset('/js/jquery.js')
    ));
  }
  
  protected function add($name, AssetManager $am)
  {
    $this->names[$name] = $am;
  } 
  
  public function necessitate($name)
  {
    
  }
}
?>
