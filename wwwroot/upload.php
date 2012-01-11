<?php
use Web10\Domain\File;

class FileUploadStatus
{
  protected $status;
  protected $message;
  protected $file;

  public function __construct($status, $message='', File $file=null)
  {
    $this->status = ($status) ? true : false;
    $this->message = $message;
    $this->file = $file;
  }

  public function __toString()
  {
    $data = array();
    $data['status'] = ($this->status) ? 'OK' : 'ERROR';
    $data['message'] = $this->message;
    if ($this->file) $data['file'] = $this->file->getJsonData();
    return json_encode($data);
  }
}

class UploadedFile
{
  protected $filename;
  protected $ext;
  protected $tmpname;

  function __construct()
  {
    $pathinfo = pathinfo($this->getName());
    $this->filename = $pathinfo['filename'];
    if (!isset($pathinfo['extension']))
    throw new InvalidArgumentException('File must have an extension.');
    $this->ext = $pathinfo['extension'];
  }

  function save()
  {
    $root = $_SERVER['DOCUMENT_ROOT'];
    $this->tmpname = tempnam("$root/data", "UP_");
    if (! move_uploaded_file($_FILES['file']['tmp_name'], $this->tmpname))
    {
      throw new Exception("The uploaded file was not valid.");
    }
  }

  public function getTmpname()
  {
    return $this->tmpname;
  }

  public function getExt()
  {
    return $this->ext;
  }

  public function getName()
  {
    return $_FILES['file']['name'];
  }

  public function getSize()
  {
    return $_FILES['file']['size'];
  }
}

class FileUploader
{
  private $disallowedExtensions = array();
  private $sizeLimit = 10485760;
  private $file;

  function __construct(array $disallowedExtensions=array(), $sizeLimit=10485760)
  {
    $disallowedExtensions = array_map("strtolower", $disallowedExtensions);

    $this->disallowedExtensions = $disallowedExtensions;
    $this->sizeLimit = $sizeLimit;

    $this->checkServerSettings();

    if (isset($_FILES['file']))
    $this->file = new UploadedFile();
    else
    throw new Exception('No file uploaded');
  }

  private function checkServerSettings()
  {
    $postSize = $this->toBytes(ini_get('post_max_size'));
    $uploadSize = $this->toBytes(ini_get('upload_max_filesize'));
    if (($postSize < $this->sizeLimit) || ($uploadSize < $this->sizeLimit))
    {
      $size = max(1, $this->sizeLimit / 1024 / 1024) . ' Mb';
      $post = max(1, $postSize / 1024 / 1024) . ' Mb';
      $upload = max(1, $uploadSize / 1024 / 1024) . ' Mb';
      throw new Exception("Increase post_max_size ($post) and upload_max_filesize ($upload) to $size");
    }
  }

  private function toMb($bytes)
  {
    return ($this->sizeLimit/1024/1024);
  }

  private function toBytes($str)
  {
    $val = trim($str);
    $last = strtolower($str[strlen($str)-1]);
    switch($last)
    {
      case 'g': $val *= 1024;
      case 'm': $val *= 1024;
      case 'k': $val *= 1024;
    }
    return $val;
  }

  function handleUpload()
  {
    $size = $this->file->getSize();
    $ext = $this->file->getExt();

    if ($size == 0)
    throw new Exception('File is empty');
    if ($size > $this->sizeLimit)
    {
      $limitMb = $this->toMb($this->sizeLimit);
      $sizeMb = $this->toMb($size);
      throw new Exception("File is too large (your file is $sizeMb Mb but the limit is $limitMb Mb");
    }

    if (($this->disallowedExtensions) && (in_array(strtolower($ext), $this->disallowedExtensions)))
    {
      $these = implode(', ', $this->disallowedExtensions);
      throw new Exception("File has an invalid extension, it cannot be one of $these");
    }

    $this->file->save();
    return $this->file;
  }
}

//
//MAIN
//

require_once 'Web10/Common/CoreClassLoader.php';
$loader = new Web10\Common\CoreClassLoader();
$loader->setup();

use \Exception;
use Web10\Web\BadControllerRequestException;
use Web10\Common\CoreContainer;
use Web10\Business\UploadManager;

//write sessionid cookie to response
$container = CoreContainer::getStatic();
$helper = $container->get('Web10\Business\CookieHelper');
$sessionId = $helper->setupVisitor();

//setup VisitorContext
$vc = $container->get('Web10\Common\Contexts\VisitorContext');
$vc->setupBySessionId($sessionId);

//setup AccountContext
$ac = $container->get('Web10\Common\Contexts\AccountContext');
$ac->setupByHostname($_SERVER['HTTP_HOST']);

$uploadManager = $container->get('Web10\Business\UploadManager');

try
{
  $disallowedExts = array("exe", "dll");
  //$allowedExts = array("jpg", "gif", "png", "pdf", "doc", "txt", "xls", "csv", "mp3");
  $uploader = new FileUploader($disallowedExts, 8*1024*1024);
  $upfile = $uploader->handleUpload();

  $parentFolderId = null;
  if (! isset($_REQUEST['parentFolderId']))
  throw new InvalidArgumentException("parentFolderId was not specified.");
  else
  $parentFolderId = $_REQUEST['parentFolderId'];
  $uploadManager->setFolderById($parentFolderId);

  //$fileRepo = new \Repository\FileRepo($em);
  //$imageRepo = new \Repository\ImageRepo($em);
  //$folderRepo = new \Repository\FolderRepo($em);
  //$uploadManager = new \Business\UploadManager($em, $context->getWebsite(), $fileRepo, $imageRepo, $folderRepo, $parentFolderId);

  $file = null;
  switch ($upfile->getExt())
  {
    case 'gif':
    case 'jpg':
    case 'jpeg':
    case 'png':
      //$uploadManager->makeThumbnails($context->getRootpath(), $upfile->getName(), $upfile->getTmpname(), $upfile->getExt());
      $file = $uploadManager->storeImage($upfile->getName(), $upfile->getTmpname(), $upfile->getExt());
      break;
    default:
      $file = $uploadManager->storeFile($upfile->getName(), $upfile->getTmpname(), $upfile->getExt());
      break;
  }

  print new FileUploadStatus(true, '', $file);
}
catch (Exception $ex)
{
  print new FileUploadStatus(false, 'Error: ' . $ex->getMessage());
}
?>
