<?
namespace Web10\Repository;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use Web10\Domain\Website;
use Web10\Domain\Page;

class UrlRepo extends DomainEntityRepo
{
  public function __construct(EntityManager $em)
  {
    parent::__construct($em, new ClassMetadata('Web10\Domain\Url'));
  }

  public function getAllByPage(Page $p)
  {
    $query = $this->_em->createQuery('select u from Web10\Domain\Url u join u.page p where p.id = :page_id');
    $query->setParameter('page_id', $p->getId());
    return $query->getResult();
  }

  public function deleteById($id)
  {
    $query = $this->_em->createQuery('delete from Web10\Domain\Url u where u.id = :url_id');
    $query->setParameter('url_id', $id);
    $query->execute();
  }

  public function deleteByPageId($pageId)
  {
    $query = $this->_em->createQuery('delete from Web10\Domain\Url u where u.id IN (select u2.id from Domain\Url u2 join u2.page p where p.id = :page_id)');
    $query->setParameter('page_id', $pageId);
    $query->execute();
  }

  public function getUrl(Website $website, $url)
  {
    $query = $this->_em->createQuery("select u from Web10\Domain\Url u join u.website w where w.id = ?1 and u.url = ?2");
    $query->setParameter(1, $website->getId());
    $query->setParameter(2, $url);

    return $query->getSingleResult();
  }
}
?>
