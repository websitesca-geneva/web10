<?
namespace Web10\Editing\ImagesAndFiles;

use Web10\Business\ImagesAndFilesManager;
use Web10\Web\FormHelper;
use Web10\Web\DialogTab;

class EditFolderTab extends DialogTab
{
  protected $manager;
  protected $folderId;

  public function __construct(ImagesAndFilesManager $manager, $folderId)
  {
    $this->manager = $manager;
    $this->folderId = $folderId;
    parent::__construct('Edit Folder');
  }

  protected function getBody()
  {
    $formHelper = new FormHelper();
    $folder = $this->manager->getFolder($this->folderId);

    //TODO:create a select for the parent folder so that folders can be MOVED
    $html  = $this->formStart("/Editing/ImagesAndFiles/saveFolder", array('folderId'=>$this->folderId));
    $html .= "<table class='formTable'>";
    $html .= "<tr><td>Folder Name</td><td>" . $formHelper->inputText('name', $folder->getName()) . "</td></tr>";
    $html .= "<tr><td colspan='2'>
      <a href='javascript:void(0);' onclick='javascript:PageManager_saveFolderClick(this);'>Save Folder</a> / 
      <a href='javascript:void(0);' onclick='javascript:PageManager_deleteFolderClick(this, {$this->folderId});'>Delete Folder</a>
      </td></tr>";
    $html .= "</table>";
    $html .= $this->formEnd();
    return $html;
  }
}
?>
