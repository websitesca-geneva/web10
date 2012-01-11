<?
namespace Web10\Repository;

use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NoResultException;

class AccountRepo extends DomainEntityRepo
{
  public function __construct(EntityManager $em)
  {
    parent::__construct($em, new ClassMetadata('Web10\Domain\Account'));
  }
  
  public function findAccountsByEmailStartingWith($str)
  {
    $query = $this->_em->createQuery("select a from Web10\Domain\Account a where a.email like ?1");
    $query->setParameter(1, $str . '%');
    return $query->getResult();
  }
  
  public function findAccountsByHostStartingWith($str)
  {
    //$query = $this->_em->createQuery("select a from Web10\Domain\Host h join h.website w join w.account a where h.hostname like ?1");
    $query = $this->_em->createQuery("select a from Web10\Domain\Host h, Web10\Domain\Website w, Web10\Domain\Account a where h.website = w and w.account = a and h.hostname like ?1");
    $query->setParameter(1, $str.'%');
    return $query->getResult();
  }
  
  public function getAccountByEmail($email)
  {
    try 
    {
      $query = $this->_em->createQuery("select a from Web10\Domain\Account a where a.email = ?1");
      $query->setParameter(1, $email);
      return $query->getSingleResult();
    }
    catch (NoResultException $ex)
    {
      return null;
    }
  }

  public function getAccount($email, $password)
  {
    $query = $this->_em->createQuery("select a from Web10\Domain\Account a where a.email = ?1 and a.password = ?2");
    $query->setParameter(1, $email);
    $query->setParameter(2, $password);
    return $query->getSingleResult();
  }
}
?>
