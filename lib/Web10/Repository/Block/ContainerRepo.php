<?
namespace Web10\Repository\Block;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;

class ContainerRepo extends BlockRepo
{
  public function __construct(EntityManager $em)
  {
    parent::__construct($em, 'Web10\Domain\Blocks\Container');
  }
}
?>
