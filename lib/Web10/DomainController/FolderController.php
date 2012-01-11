<?php
namespace Web10\DomainController;

use Web10\Business\FileManager;
use Web10\Domain\Folder;
use Web10\Common\Contexts\AccountContext;

class FolderController
{
  protected $manager;

  public function __construct(FileManager $manager, AccountContext $ac)
  {
    $this->manager = $manager;
    $this->website = $ac->getWebsite();
  }

  public function create($data)
  {
    $folder = $this->manager->addFolder($data->parentFolderId, $data->name);
    return $folder;
  }

  public function delete($data, $id)
  {
    $this->manager->deleteFolder($id);
  }

  public function update($data, $id)
  {
    $f = $this->manager->getFolder($id);
    $f->setName($data->name);
    $this->manager->saveFolder($f);
    return $f;
  }
}
?>
