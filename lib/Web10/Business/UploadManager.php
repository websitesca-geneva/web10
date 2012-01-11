<?
namespace Web10\Business;

use Doctrine\ORM\EntityManager;
use Web10\Domain\Website;
use Web10\Web\HttpContext;
use Web10\Repository\FileRepo;
use Web10\Repository\ImageRepo;
use Web10\Repository\FolderRepo;
use Web10\Domain\File;
use Web10\Domain\Image;
use Web10\Common\Contexts\AccountContext;

class UploadManager
{
  protected $website;
  protected $fileRepo;
  protected $imageRepo;
  protected $folderRepo;
  protected $datapath;
  protected $folder;
  protected $context;

  public function __construct(AccountContext $ac, FileRepo $fileRepo, ImageRepo $imageRepo, FolderRepo $folderRepo, $datapath)
  {
    $this->website = $ac->getWebsite();//$context->getWebsite();
    $this->fileRepo = $fileRepo;
    $this->imageRepo = $imageRepo;
    $this->folderRepo = $folderRepo;
    $this->datapath = $datapath;
    $this->folder = null;
  }

  public function setThumbnailer(Thumbnailer $thumbnailer)
  {
    $this->thumbnailer = $thumbnailer;
  }

  public function setFolder(Folder $folder)
  {
    $this->folder = $folder;
  }

  public function setFolderById($folderId)
  {
    if (!empty($folderId))
    {
      $this->folder = $this->folderRepo->getById($folderId);
    }
  }

  private function removeExt($origname, $ext)
  {
    //remove the ext from the orig name
    if (substr($origname, -strlen($ext)) == $ext)
    {
      $origname = substr($origname, 0, -strlen($ext)-1);
    }
    return $origname;
  }

  public function storeFile($origname, $tmpname, $ext)
  {
    $origname = $this->removeExt($origname, $ext);

    //get an id
    $file = new File();
    $file->setWebsite($this->website);
    $file->setExt($ext);
    $file->setName($origname);
    $file->setFolder($this->folder);
    $this->fileRepo->saveAndFlush($file);

    $fileId = $file->getId();
    $websiteId = $this->website->getId();
    $accountId = $this->website->getAccount()->getId();

    $path = "$this->datapath/account_$accountId/website_$websiteId/file_$fileId.$ext";
    rename($tmpname, $path);

    return $file;
  }

  public function storeImage($origname, $tmpname, $ext, $deleteAfter=true)
  {
    $websiteId = $this->website->getId();
    $accountId = $this->website->getAccount()->getId();

    $this->thumbnailer->load($tmpname, $ext);
    $width = $this->thumbnailer->getWidth();
    $height = $this->thumbnailer->getHeight();

    $origname = $this->removeExt($origname, $ext);

    //get an imageId
    $image = new Image();
    $image->setWebsite($this->website);
    $image->setExt($ext);
    $image->setName($origname);
    $image->setFolder($this->folder);
    $image->setWidth($width);
    $image->setHeight($height);
    $this->imageRepo->saveAndFlush($image);

    //now create the thumbnails and move to the correct directory
    $this->thumbnailer->createThumbs($image);

    if ($deleteAfter)
    {
      unlink($tmpname);
    }

    return $image;
  }
}
