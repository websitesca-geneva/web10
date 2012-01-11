<?
namespace Web10\Editing\ImagesAndFiles;

use Web10\Business\ImagesAndFilesManager;
use Web10\Web\FormHelper;
use Web10\Web\DialogTab;

class EditFileTab extends DialogTab
{
  protected $manager;
  protected $fileId;

  public function __construct(ImagesAndFilesManager $manager, $fileId)
  {
    $this->manager = $manager;
    $this->fileId = $fileId;
    parent::__construct('Edit File');
  }

  protected function getBody()
  {
    $formHelper = new FormHelper();
    $file = $this->manager->getFile($this->fileId);

    //TODO:create a select for the parent file so that files can be MOVED
    $html  = $this->formStart("/Editing/ImagesAndFiles/saveFile", array('fileId'=>$this->fileId));
    $html .= "<table class='formTable'>";
    $html .= "<tr><td>file Name</td><td>" . $formHelper->inputText('name', $file->getName()) . "</td></tr>";
    $html .= "<tr><td colspan='2'>
      <a href='javascript:void(0);' onclick='javascript:PageManager_saveFileClick(this);'>Save file</a> / 
      <a href='javascript:void(0);' onclick='javascript:PageManager_deleteFileClick(this, {$this->fileId});'>Delete file</a>
      </td></tr>";
    $html .= "</table>";
    $html .= $this->formEnd();
    return $html;
  }
}
?>
