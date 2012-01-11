<?
namespace Web10\Repository;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use Web10\Domain\Website;

class PageRepo extends DomainEntityRepo
{
  public function __construct(EntityManager $em)
  {
    parent::__construct($em, new ClassMetadata('Web10\Domain\Page'));
  }

  public function hasSubpages($pageId)
  {
    $query = $this->_em->createQuery('select count(p.id) from Web10\Domain\Page p join p.parent par where par.id = :page_id');
    $query->setParameter('page_id', $pageId);
    $count = $query->getSingleScalarResult();
    return $count > 0;
  }

  public function deleteById($pageId)
  {
    $query = $this->_em->createQuery('delete from Web10\Domain\Page p where p.id = :page_id');
    $query->setParameter('page_id', $pageId);
    $query->execute();
  }

  public function getAllByWebsite(Website $website)
  {
    $query = $this->_em->createQuery('select p, par from Web10\Domain\Page p
      join p.website w left join p.parent par where w.id = :website_id');
    $query->setParameter('website_id', $website->getId());
    return $query->getResult();
  }

  public function getAllOrdered(Website $website)
  {
    $query = $this->_em->createQuery('select p, par from Web10\Domain\Page p
      join p.website w left join p.parent par where w.id = :website_id order by p.ordering');
    $query->setParameter('website_id', $website->getId());
    return $query->getResult();
  }
}
?>
