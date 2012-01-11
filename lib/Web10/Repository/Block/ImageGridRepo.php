<?
namespace Web10\Repository\Block;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use Web10\Domain\Blocks\ImageGrid;

class ImageGridRepo extends BlockRepo
{
  public function __construct(EntityManager $em)
  {
    parent::__construct($em, 'Web10\Domain\Blocks\ImageGrid');
  }
  
  public function getAllImagesOrdered(ImageGrid $imageGrid)
  {
    $query = $this->_em->createQuery('select i from Web10\Domain\Block\ImageGrid_Image i
      join Web10\Domain\Block\ImageGrid g where g.id = :id order by i.ordering');
    $query->setParameter('id', $imageGrid->getId());
    return $query->getResult();
  }
}
?>
