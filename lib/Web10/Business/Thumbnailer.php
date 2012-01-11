<?php
namespace Web10\Business;

use \InvalidArgumentException;
use Web10\Domain\Website;
use Web10\Domain\Image;
use Web10\Common\Contexts\AccountContext;

class Thumbnailer
{
  protected $accountId;
  protected $websiteId;
  protected $datapath;

  protected $img;
  protected $image;
  protected $width;
  protected $height;

  public function __construct(AccountContext $ac, $datapath)
  {
    $website = $ac->getWebsite();
    $this->websiteId = $website->getId();
    $this->accountId = $website->getAccount()->getId();
    $this->datapath = $datapath;
  }

  function __destruct()
  {
    if (is_resource($this->img))
    {
      imagedestroy($this->img);
    }
  }

  public function load($tmpUploadedFilename, $ext)
  {
    if ($ext == 'jpg' or $ext == 'jpeg')
	    $this->img = imagecreatefromjpeg($tmpUploadedFilename);
    elseif ($ext == 'gif')
    	$this->img = imagecreatefromgif($tmpUploadedFilename);
    elseif ($ext == 'png')
    	$this->img = imagecreatefrompng($tmpUploadedFilename);
    else
    	throw new InvalidArgumentException("The image type $ext is not supported.");

    $this->width = imagesx($this->img);
    $this->height = imagesy($this->img);
  }

  public function getWidth() { return $this->width; }
  public function getHeight() { return $this->height; }

  public function createThumbs(Image $image)
  {
    $this->image = $image;
     
    $this->createThumb(100);
    $this->createThumb(200);
    $this->createThumb(300);
    $this->createThumb(500);
    $this->createThumb(700);
    $this->createThumb(900);
    $this->createThumb(1200);

    imagedestroy($this->img);
  }
  
  protected function createThumb($maxDimension, $sizeCode=null)
  {
    $imageId = $this->image->getId();
    $ext = strtolower($this->image->getExt());
    $sizeCode = ($sizeCode) ? $sizeCode : "$maxDimension";
    $scale = min($maxDimension/$this->width, $maxDimension/$this->height);
    $newWidth = floor($this->width * $scale);
    $newHeight = floor($this->height * $scale);
    $tmpImg = imagecreatetruecolor($newWidth, $newHeight);
    imagecopyresampled($tmpImg, $this->img, 0, 0, 0, 0, $newWidth, $newHeight, $this->width, $this->height);
    $filepath = "{$this->datapath}/account_{$this->accountId}/website_{$this->websiteId}/image_{$imageId}_{$sizeCode}.{$ext}";

    if ($ext == 'jpg' or $ext == 'jpeg')
    	imagejpeg($tmpImg, $filepath);
    elseif ($ext == 'gif')
    	imagegif($tmpImg, $filepath);
    elseif ($ext == 'png')
    	imagepng($tmpImg, $filepath);
    else
    	throw new InvalidArgumentException("The image type {$ext} is not supported.");

    imagedestroy($tmpImg);
  }
}
?>
