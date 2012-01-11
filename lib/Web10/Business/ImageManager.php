<?php
namespace Web10\Business;

use Web10\Repository\ImageRepo;
use Web10\Domain\Image;

class ImageManager
{
  protected $repo;

  public function __construct(ImageRepo $repo)
  {
    $this->repo = $repo;
  }

  public function getImage($id)
  {
    return $this->repo->getById($id);
  }
}
?>
