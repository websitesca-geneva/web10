<?
namespace Web10\Repository;

use Doctrine\ORM\EntityRepository;

class DomainEntityRepo extends EntityRepository
{
  public function getById($id)
  {
    return $this->findOneBy(array("id"=>$id));
  }

  public function save($entity)
  {
    $this->_em->persist($entity);
  }

  public function saveAndFlush($entity)
  {
    $this->_em->persist($entity);
    $this->_em->flush();
  }

  public function delete($entity)
  {
    $this->_em->remove($entity);
  }

  public function deleteAndFlush($entity)
  {
    $this->_em->remove($entity);
    $this->_em->flush();
  }
}
?>
