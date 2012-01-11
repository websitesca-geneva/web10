<?
namespace Web10\Editing\ImagesAndFiles;

use Web10\Web\BaseController;
use Web10\Business\ImagesAndFilesManager;
use Web10\Web\ControllerResponseOK;
use Web10\Web\ControllerResponseError;

class Controller extends BaseController
{
  protected $manager;

  public function __construct(ImagesAndFilesManager $manager)
  {
    parent::__construct();
    $this->manager = $manager;
  }

  public function managerDialog($parentFolderId)
  {
    return new ControllerResponseOK(new ManagerDialog($this->manager, $parentFolderId));
  }

  public function addFolder($parentFolderId, $name)
  {
    $this->manager->addFolder($parentFolderId, $name);
    return new ControllerResponseOK(new FileList($this->manager, $parentFolderId), "Folder added!");
  }

  public function folder($parentFolderId)
  {
    $newPath = $this->manager->getPathToFolder($parentFolderId);
    $help = new Helper();
    $submenu = $help->getSubmenu($newPath);
    $extra = array('submenu'=>$submenu);
    return new ControllerResponseOK(new FileList($this->manager, $parentFolderId), null, $extra);
  }

  public function editFolderTab($folderId)
  {
    return new ControllerResponseOK(new EditFolderTab($this->manager, $folderId));
  }

  protected function getFileListResponse($parent, $msg)
  {
    $parentFolderId = null;
    if ($parent)
    $parentFolderId = $parent->getId();
    $help = new Helper();
    $newPath = $this->manager->getPathToFolder($parentFolderId);
    $submenu = $help->getSubmenu($newPath);
    $extra = array('submenu'=>$submenu);
    return new ControllerResponseOK(new FileList($this->manager, $parentFolderId), $msg, $extra);
  }

  public function saveFolder($folderId, $name)
  {
    $folder = $this->manager->getFolder($folderId);
    $this->manager->saveFolder($folderId, $name);
    return $this->getFileListResponse($folder->getParent(), "Folder saved!");
  }

  public function deleteFolder($folderId)
  {
    //first check if the folder has subitems
    if ($this->manager->folderHasContents($folderId))
    {
      return new ControllerResponseError("The folder must be empty to delete it.");
    }
    $folder = $this->manager->getFolder($folderId);
    $this->manager->deleteFolder($folderId);
    return $this->getFileListResponse($folder->getParent(), "Folder deleted!");
  }

  public function editFileTab($fileId)
  {
    return new ControllerResponseOK(new EditFileTab($this->manager, $fileId));
  }

  public function saveFile($fileId, $name)
  {
    $this->manager->saveFile($fileId, $name);
    $folder = $this->manager->getFolderByFile($fileId);
    return $this->getFileListResponse($folder, "File saved!");
  }

  public function deleteFile($fileId)
  {
    $folder = $this->manager->getFolderByFile($fileId);
    $this->manager->deleteFile($fileId);
    return $this->getFileListResponse($folder, "File deleted!");
  }
}
