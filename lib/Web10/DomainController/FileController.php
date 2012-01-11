<?php
namespace Web10\DomainController;

use Web10\Common\JsonCollection;
use Web10\Business\FileManager;
use Web10\Domain\File;
use Web10\Domain\Image;
use Web10\Common\Contexts\AccountContext;

class FileController
{
  protected $manager;

  public function __construct(FileManager $manager, AccountContext $ac)
  {
    $this->manager = $manager;
    $this->website = $ac->getWebsite();
  }

  public function delete($data, $id)
  {
    $this->manager->deleteFile($id);
    return null;
  }

  public function update($data, $id)
  {
    $file = $this->manager->getFile($id);
    $file->setName($data->name);
    $this->manager->saveFile($file);
    return $file;
  }
}
?>
