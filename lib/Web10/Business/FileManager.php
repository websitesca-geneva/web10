<?
namespace Web10\Business;

use Doctrine\ORM\EntityManager;
use Web10\Domain\Website;
use Web10\Repository\FolderRepo;
use Web10\Repository\FileRepo;
use Web10\Repository\ImageRepo;
use Web10\Domain\Folder;
use Web10\Domain\File;
use Web10\Domain\Image;
use Web10\Common\Contexts\AccountContext;

class FileManager
{
  protected $em;
  protected $website;
  protected $fileRepo;
  protected $imageRepo;
  protected $folderRepo;

  public function __construct(EntityManager $em, AccountContext $ac)
  {
    $this->em = $em;
    $this->website = $ac->getWebsite();
  }

  public function setFolderRepo(FolderRepo $folderRepo) { $this->folderRepo = $folderRepo; }
  public function setFileRepo(FileRepo $fileRepo) { $this->fileRepo = $fileRepo; }
  public function setImageRepo(ImageRepo $imageRepo) { $this->imageRepo = $imageRepo; }

  public function getFilesInside(Folder $parent=null)
  {
    $files = $this->fileRepo->getAllByFolder($this->website, $parent);
    return $files;
  }

  public function getAllFiles()
  {
    return $this->fileRepo->getAll($this->website);
  }

  public function getAllFolders()
  {
    return $this->folderRepo->getAll($this->website);
  }

  public function getFoldersInside(Folder $parent=null)
  {
    $folders = $this->folderRepo->getAllByFolder($this->website, $parent);
    return $folders;
  }

  public function getFolder($folderId)
  {
    if (empty($folderId))
    return null;
    else
    return $this->folderRepo->getById($folderId);
  }

  public function getFolderByFile($fileId)
  {
    $file = $this->fileRepo->getById($fileId);
    return $file->getFolder();
  }

  public function getPathToFolder($folderId)
  {
    $path = array();
    while (!empty($folderId))
    {
      $folder = $this->folderRepo->getById($folderId);
      $path[] = $folder;
      $parent = $folder->getParent();
      if ($parent)
      $folderId = $parent->getId();
      else
      $folderId = null;
    }
    return array_reverse($path);
  }

  public function addFolder($parentFolderId, $name)
  {
    $parent = $this->folderRepo->getById($parentFolderId);
    $folder = new Folder();
    $folder->setWebsite($this->website);
    $folder->setName($name);
    $folder->setParent($parent);
    $this->folderRepo->saveAndFlush($folder);
    return $folder;
  }

  public function saveFolder(Folder $f)
  {
    $this->folderRepo->saveAndFlush($f);
  }

  public function renameFolder($folderId, $name)
  {
    $folder = $this->getFolder($folderId);
    $folder->setName($name);
    $this->folderRepo->saveAndFlush($folder);
    return $folder;
  }

  public function folderHasContents($folderId)
  {
    $folder = $this->folderRepo->getById($folderId);
    $folderCount = $this->folderRepo->getCountInside($this->website, $folder);
    $fileCount = $this->fileRepo->getCountInside($this->website, $folder);
    return (($folderCount > 0) or ($fileCount > 0));
  }

  public function deleteFolder($folderId)
  {
    $folder = $this->folderRepo->getById($folderId);
    $this->folderRepo->deleteAndFlush($folder);
  }

  public function getFile($fileId)
  {
    return $this->fileRepo->getById($fileId);
  }

  public function saveFile(File $file)
  {
    $this->fileRepo->saveAndFlush($file);
  }

  public function renameFile($fileId, $name)
  {
    $file = $this->getFile($fileId);
    $file->setName($name);
    $this->fileRepo->saveAndFlush($file);
    return $file;
  }

  public function deleteFile($fileId)
  {
    $file = $this->fileRepo->getById($fileId);
    $this->fileRepo->deleteAndFlush($file);
  }
}
?>
