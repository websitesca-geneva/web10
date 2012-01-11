<?php
namespace AssetManager;

use AssetManager\Asset;

class JsAsset extends FileAsset
{
  public function __toString()
  {
    return "JsAsset(" . $this->getUrl() . ")";
  }
}
?>
