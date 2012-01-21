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
  
  /** @Column(type="text") */
  protected $allowedTypes;
  public function getAllowedTypes() { return $this->allowedTypes; }
  public function setAllowedTypes($val) { $this->allowedTypes = $val; }

  public function __construct()
  {
    parent::__construct();
    $this->blocks = new ArrayCollection();
  }

  public function getAllowedTypesArray() 
  {
    $types = array();
    $a = preg_split('/[,]+/', $this->allowedTypes);
    foreach ($a as $type)
    {
      $types[] = trim($type);
    }
    return $types;
  }
  
  public function getJsonData()
  {
    $data = parent::getJsonDataBase();
    $data['blocks'] = array();
    $data['allowedTypes'] = $this->getAllowedTypesArray();
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