<?
namespace Web10\Editing\ImagesAndFiles;

use Web10\Web\DialogTabbed;
use Web10\Business\ImagesAndFilesManager;
use Web10\Web\FormHelper;

class ManagerDialog extends DialogTabbed
{
  protected $manager;
  protected $folderId;
  protected $folder;
  protected $pathToFolder;

  public function __construct(ImagesAndFilesManager $manager, $folderId)
  {
    $params = array();
    parent::__construct(false, null, null);
    $this->title = "Manage Images & Files";
    $this->manager = $manager;
    $this->folderId = $folderId;
    $this->pathToFolder = $this->manager->getPathToFolder($folderId);

    $this->addTab("Browse", $this->browseTab());
    $this->addTab("Add Folder", $this->addFolderTab());
    $this->addTab("Upload Files", $this->uploadTab());
  }

  protected function uploadTab()
  {
    $help = new Helper();
    $html  = $help->getSubmenuWithWrapper($this->pathToFolder);
    $html .= "<div class='dropzone'>";
    $html .= "<p>Drop your files into this box to upload.</p><p>They will be saved into the current folder.</p>";
    $html .= "<div class='status'></div>";
    $html .= "</div>";
    return $html;
  }

  protected function browseTab()
  {
    $fileList = new FileList($this->manager, $this->folderId);
    $help = new Helper();
    $html  = $help->getSubmenuWithWrapper($this->pathToFolder);
    $html .= "<div class='filesandfolders-wrapper'>";
    $html .= $fileList->getHtmlData();
    $html .= "</div>";
    return $html;
  }

  protected function addFolderTab()
  {
    $formHelper = new FormHelper();
    $help = new Helper();
    $html  = $help->getSubmenuWithWrapper($this->pathToFolder);
    $html .= $formHelper->formStart('/Editing/ImagesAndFiles/addFolder', 'addFolderForm');
    $html .= "<p>Add a folder inside the current folder.";
    $html .= "<p>Folder Name: " . $formHelper->inputText('name');
    $html .= "<p>" . $formHelper->button('addFolder', 'Add Folder', 'ImagesAndFiles_addFolderClick');
    $html .= $formHelper->formEnd();
    return $html;
  }
}
?>
