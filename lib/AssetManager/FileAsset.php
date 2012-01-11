<?php
namespace AssetManager;

abstract class FileAsset extends Asset
{
  protected $url;

  public function __construct($url)
  {
    $this->url = $url;
  }

  public function getUrl()
  {
    return $this->url;
  }
}
?>
