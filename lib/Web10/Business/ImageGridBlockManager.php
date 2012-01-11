<?
namespace Web10\Business;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NoResultException;
use Web10\Repository\ImageGrid_ImageRepo;
use Web10\Repository\Block\ImageGridRepo;
use Web10\Common\Contexts\AccountContext;
use Web10\Domain\Blocks\ImageGrid;

class ImageGridBlockManager
{
  protected $repo;
  protected $website;

  public function __construct(ImageGridRepo $repo, AccountContext $ac, EntityManager $em)
  {
    $this->repo = $repo;
    $this->website = $ac->getWebsite();
    $this->em = $em;
  }

  public function saveImageGridBlock(ImageGrid $b)
  {
    $this->repo->saveAndFlush($b);
  }
  
  public function getAllImagesOrdered(ImageGrid $imageGrid)
  {
    return $this->repo->getAllImagesOrdered($imageGrid);
  }

  public function getImageGridBlock($id)
  {
    return $this->repo->getBlockById($this->website, $id);
  }
  
  public function clearImages(ImageGrid $imageGrid)
  {
    foreach ($imageGrid->getImages() as $imageItem)
    {
      $this->em->remove($imageItem);
    }
    $imageGrid->clearImages();
  }
}
?>
