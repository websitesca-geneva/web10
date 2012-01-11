<?
namespace Web10\Editing\ImagesAndFiles;

class Helper
{
  public function getSubmenuWithWrapper($pathToFolder)
  {
    $html  = "<div class='submenu-wrapper'>";
    $html .= $this->getSubmenu($pathToFolder);
    $html .= "</div>";
    return $html;
  }

  public function getSubmenu($pathToFolder)
  {
    $pathStr = $this->getPathToFolderStr($pathToFolder);
    return "<div class='submenu'>
      <div class='path'>Current Folder: <span>{$pathStr}</span></div>
      </div>";
  }

  public function getPathToFolderStr($pathToFolder)
  {
    $str = "<a href='javascript:void(0);' onclick='ImagesAndFiles_folderClick(this, 0);'>Root</a> / ";
    foreach ($pathToFolder as $folder)
    {
      $name = $folder->getName();
      $id = $folder->getId();
      $str .= "<a href='javascript:void(0);' onclick='ImagesAndFiles_folderClick(this, $id);'>$name</a> / ";
    }
    return $str;
  }
}
?>
