<?
namespace Web10\Repository;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;

class VisitorRepo extends DomainEntityRepo
{
  public function __construct(EntityManager $em)
  {
    parent::__construct($em, new ClassMetadata('Web10\Domain\Visitor'));
  }

  public function getBySessionId($id)
  {
    $query = $this->_em->createQuery("select v from Web10\Domain\Visitor v where v.sessionId = ?1");
    $query->setParameter(1, $id);
    return $query->getSingleResult();
  }
}
?>
