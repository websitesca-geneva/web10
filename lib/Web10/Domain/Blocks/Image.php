<?
namespace Web10\Domain\Blocks;

use Web10\Common\JsonEntity;

/** @Entity @Table(name="block_image") */
class Image extends Block implements JsonEntity
{
  /** @Id @Column(type="integer") @GeneratedValue */
  protected $id;
  public function getId() { return $this->id; }
  public function setId($id) { $this->id = $id; }

  /** @ManyToOne(targetEntity="\Web10\Domain\Image", inversedBy="files") */
  protected $image;
  public function getImage() { return $this->image; }
  public function setImage($val) { $this->image = $val; }

  /** @Column(type="text") */
  protected $defaultSrc;
  public function getDefaultSrc() { return $this->defaultSrc; }
  public function setDefaultSrc($val) { $this->defaultSrc = $val; }

  public function __construct()
  {
    parent::__construct();
    $this->setDefaultSrc('/img/close.jpg');
  }

  public function getJsonData()
  {
    $data = parent::getJsonDataBase();
    if ($this->image)
    $data['imageId'] = $this->image->getId();
    $data['defaultSrc'] = $this->defaultSrc;
    return $data;
  }

  public function __toString()
  {
    return json_encode($this->getJsonData());
  }
}
?>
