<?php
namespace Web10\Editing\ImagesAndFiles;

use Web10\Web\HtmlEntity;
use Web10\Business\ImagesAndFilesManager;
use Web10\Domain\Folder;
use Web10\Domain\Image;

class FileList implements HtmlEntity
{
  protected $manager;
  protected $parent;

  public function __construct(ImagesAndFilesManager $manager, $parentFolderId)
  {
    $this->manager = $manager;
    $this->parent = $this->manager->getFolder($parentFolderId);
  }

  public function getHtmlData()
  {
    $folders = $this->manager->getFoldersInside($this->parent);
    $files = $this->manager->getFilesInside($this->parent);

    $html  = "<div class='filesandfolders'>";
    $html .= "<div class='folderlist'>";
    foreach ($folders as $folder)
    {
      $name = $folder->getName();
      $id = $folder->getId();
      $html .= "<div class='folder'>
        <a title='$name' href='javascript:void(0);' onclick='ImagesAndFiles_folderClick(this, $id);'>$name</a> / 
        <a href='javascript:void(0);' onclick='ImagesAndFiles_folderEdit(this, $id);'>Edit</a>
        <br><br>[FOLDERICON]</div>";
    }
    $html .= "</div>";
    $html .= "<div class='filelist'>";
    foreach ($files as $file)
    {
      $name = $file->getName();
      $ext = $file->getExt();
      $id = $file->getId();
      if ($file instanceof Image)
      {
        $path = $file->getRelativePath(100);
        $html .= "<div class='file'>
          <img src='$path'><br>$name ($ext) / 
          <a href='javascript:void(0);' onclick='ImagesAndFiles_fileEdit(this, $id);'>Edit</a>
          </div>";
      }
      else
      $html .= "<div class='file'>$name ($ext) /
          <a href='javascript:void(0);' onclick='ImagesAndFiles_fileEdit(this, $id);'>Edit</a>
          <br><br>[FILEICON]
          </div>";
    }
    $html .= "</div>";
    $html .= "<div class='clear'></div>";
    $html .= "</div>";
    return $html;
  }

  public function __toString()
  {
    return $this->getHtmlData();
  }
}
?>
