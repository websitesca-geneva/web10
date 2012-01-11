<?php
namespace AssetManager;

use AssetManager\FileAsset;
use \InvalidArgumentException;

class AssetManager
{
  protected $rootpathweb;
  protected $assets;

  public function __construct($rootpathweb)
  {
    $this->assets = array();
    $this->rootpathweb = $rootpathweb;
  }

  public function fileAssetExists(FileAsset $fileAsset)
  {
    foreach ($this->assets as $asset)
    {
      if ($asset instanceof FileAsset)
      {
        if ($asset->getUrl() == $fileAsset->getUrl())
        {
          return true;
        }
      }
    }
    return false;
  }
  
  public function merge(AssetManager $am)
  {
    foreach ($am->getAll() as $asset)
    {
      $this->addAsset($asset);
    }
  }
  
  public static function mergeAll()
  {
    $am = new AssetManager($this->rootpathweb);
    foreach (func_get_args() as $a)
    {
      if ($a instanceof AssetManager)
      {
        $am->merge($a);
      }
      else 
        throw new InvalidArgumentException("mergeAll only takes arguments of type AssetManager.");
    }
    return $am;
  }
  
  public function addAsset(Asset $asset)
  {
    if ($asset instanceof FileAsset)
    {
      //check if the file exists
      if (! file_exists("{$this->rootpathweb}{$asset->getUrl()}"))
      {
        throw new InvalidArgumentException("FileAsset {$this->rootpathweb}{$asset->getUrl()} does not exist.");
      }

      if ($this->fileAssetExists($asset))
      {
        //throw new InvalidArgumentException("The asset {$asset->getUrl()} already exists.");
        return;
      }
    }    
    if (in_array($asset, $this->assets))
    {
      return;
    }
     
    $this->assets[] = $asset;
    $type = get_class($asset);
    if (!isset($this->byType[$type]))
    {
      $this->byType[$type] = array();
    }
    $this->byType[$type][] = $asset;

  }
  
  protected function mapFileExtToAssetTypename($ext)
  {
    switch (strtolower($ext))
    {
      case 'js':  return 'AssetManager\JsAsset';
      case 'css': return 'AssetManager\CssAsset';
      default: throw new InvalidArgumentException("Unknown file extention $ext.");
    }
  }
  
  public function addFileAssets($glob)
  {
    $glob = $this->rootpathweb . $glob;
    $files = glob($glob);
    foreach ($files as $file)
    {
      if (is_dir($file)) continue;
      $url = substr($file, strlen($this->rootpathweb));
      $info = pathinfo($file);
      $ext = $info['extension'];
      $typename = $this->mapFileExtToAssetTypename($ext);
      $a = new $typename($url);
      $this->addAsset($a);
    }
  }

  public function getAll($type=null)
  {
    return $this->assets;
  }

  public function getAllByType($type)
  {
    if (isset($this->byType[$type]))
      return $this->byType[$type];
    else
      return array();
  }
}
?>
