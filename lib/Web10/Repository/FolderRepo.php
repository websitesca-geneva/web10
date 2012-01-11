<?
namespace Web10\Repository;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use Web10\Domain\Website;
use Web10\Domain\Folder;

class FolderRepo extends DomainEntityRepo
{
  public function __construct(EntityManager $em)
  {
    parent::__construct($em, new ClassMetadata('Web10\Domain\Folder'));
  }

  public function getAll(Website $website)
  {
    $dql = "select f from Web10\Domain\Folder f join f.website w where w.id = :website_id";
    $query = $this->_em->createQuery($dql);
    $query->setParameter('website_id', $website->getId());
    return $query->getResult();
  }

  public function getAllByFolder(Website $website, Folder $parent=null)
  {
    if ($parent)
    $dql = "select f from Web10\Domain\Folder f join f.website w join f.parent p where w.id = :website_id and p.id = :parent_folder_id";
    else
    $dql = "select f from Web10\Domain\Folder f join f.website w where w.id = :website_id and f.parent is null";
    $query = $this->_em->createQuery($dql);
    $query->setParameter('website_id', $website->getId());
    if ($parent)
    $query->setParameter('parent_folder_id', $parent->getId());
    return $query->getResult();
  }

  public function getCountInside(Website $website, Folder $parent=null)
  {
    if ($parent)
    $dql = "select count(f.id) from Web10\Domain\Folder f join f.website w join f.parent p where w.id = :website_id and p.id = :parent_folder_id";
    else
    $dql = "select count(f.id) from Web10\Domain\Folder f join f.website w where w.id = :website_id and f.parent is null";
    $query = $this->_em->createQuery($dql);
    $query->setParameter('website_id', $website->getId());
    if ($parent)
    $query->setParameter('parent_folder_id', $parent->getId());
    return $query->getSingleScalarResult();
  }
}
?>
