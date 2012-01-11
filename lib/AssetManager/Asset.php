<?php
namespace AssetManager;

abstract class Asset
{
  public function __construct()
  {
  }

  public function __toString()
  {
    return 'Asset';
  }
}
?>
