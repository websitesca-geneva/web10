<?
namespace Web10\Domain\Blocks;

use Doctrine\Common\Collections\ArrayCollection;

/** @Entity @Table(name="block_container") */
class Container extends Block
{
  /** @Id @Column(type="integer") @GeneratedValue */
  protected $id;
  public function getId() { return $this->id; }
  public function setId($id) { $this->id = $id; }

  /** @OneToMany(targetEntity="Block", mappedBy="container", cascade={"all"}) */
  protected $blocks;
  public function getBlocks() { return $this->blocks; }
  public function setBlocks($val) { $this->blocks = $val; }
  public function addBlock($b) { $this->blocks[] = $b; }
  public function getBlockCount() { return count($this->blocks); }

  public function removeBlockById($id)
  {
    $count = 0;
    foreach ($this->blocks as $b)
    {
      if ($b->getId() == $id)
      {
        unset($this->blocks[$count]);
        break;
      }
      $count++;
    }
  }

  public function __construct()
  {
    parent::__construct();
    $this->blocks = new ArrayCollection();
  }
}
?>
