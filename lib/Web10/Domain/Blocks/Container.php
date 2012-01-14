<?
namespace Web10\Domain\Blocks;

use Web10\Common\JsonEntity;
use Doctrine\Common\Collections\ArrayCollection;

/** @Entity @Table(name="block_container") */
class Container extends Block implements JsonEntity
{ 
  /** @OneToMany(targetEntity="Web10\Domain\Blocks\Block", mappedBy="container", cascade={"all"}) */
  protected $blocks;
  public function getBlocks() { return $this->blocks; }
  public function addBlock($image) { $this->blocks[] = $image; }
  public function clearBlocks() { $this->blocks = new ArrayCollection(); }

  public function __construct()
  {
    parent::__construct();
    $this->blocks = new ArrayCollection();
  }

  public function getJsonData()
  {
    $data = parent::getJsonDataBase();
    $data['blocks'] = array();
    foreach ($this->blocks as $block) 
    {
      $data['blocks'][] = $block->getJsonData();
    }
    return $data;
  }

  public function __toString()
  {
    return json_encode($this->getJsonData());
  }
}
?>