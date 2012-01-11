<?
namespace Web10\Repository;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use Web10\Domain\Website;
use Web10\Domain\WebsiteDef;

class LayoutRepo extends DomainEntityRepo
{
  public function __construct(EntityManager $em)
  {
    parent::__construct($em, new ClassMetadata('Web10\Domain\Layout'));
  }

  /*
   public function getLayoutById($layoutId)
   {
   //    $query = $this->_em->createQuery('select l from Domain\Layout l where l.id = :layout_id');
   //    $query->setParameter('layout_id', $layoutId);
   //    return $query->getResult();
   return $this->findOneBy(array("id" => $layoutId));
   }
   */

  public function getAllByWebsiteDef(WebsiteDef $websiteDef)
  {
    $query = $this->_em->createQuery('select l from Web10\Domain\Layout l join l.websitedef d where d.id = :websitedef_id');
    $query->setParameter('websitedef_id', $websiteDef->getId());
    return $query->getResult();
  }
}
?>
