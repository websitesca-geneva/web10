<?php
namespace AssetManager;

use AssetManager\Asset;

class CssAsset extends FileAsset
{
  public function __toString()
  {
    return "CssAsset(" . $this->getUrl() . ")";
  }
}
?>
