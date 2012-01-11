<?
namespace Web10\Domain\Blocks;

use Doctrine\Common\Collections\ArrayCollection;
use Web10\Common\JsonEntity;

/** @Entity @Table(name="block_menu") */
class Menu extends Block implements JsonEntity
{
  public function __construct()
  {
  }

  /** @Column(type="integer") */
  protected $pageHierarchyDepth;
  public function getPageHierarchyDepth() { return $this->pageHierarchyDepth; }
  public function setPageHierarchyDepth($val) { $this->pageHierarchyDepth = $val; }

  public function getJsonData()
  {
    $data = parent::getJsonDataBase();
    $data['pageHierarchyDepth'] = $this->getPageHierarchyDepth();
    return $data;
  }

  public function __toString()
  {
    return json_encode($this->getJsonData());
  }
}
?>
