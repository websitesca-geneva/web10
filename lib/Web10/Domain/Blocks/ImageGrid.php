<?
namespace Web10\Domain\Blocks;

use Web10\Common\JsonEntity;
use Doctrine\Common\Collections\ArrayCollection;

/** @Entity @Table(name="block_imagegrid") */
class ImageGrid extends Block implements JsonEntity
{
  /** @Column(type="text") */
  protected $sizeCode;
  public function getSizeCode() { return $this->sizeCode; }
  public function setSizeCode($val) { $this->sizeCode = $val; }
  
  /** @OneToMany(targetEntity="Web10\Domain\Blocks\ImageGrid_Image", mappedBy="imageGrid", cascade={"all"}) */
  protected $images;
  public function getImages() { return $this->images; }
  public function setImages($val) { $this->images = $val; }
  public function addImage($image) { $this->images[] = $image; }
  public function clearImages() { $this->images = new ArrayCollection(); }

  public function __construct()
  {
    parent::__construct();
    $this->sizeCode = 200;
    $this->images = new ArrayCollection();
  }

  public function getJsonData()
  {
    $data = parent::getJsonDataBase();
    $data['sizeCode'] = $this->sizeCode;
    $data['images'] = array();
    foreach ($this->images as $image) 
    {
      $data['images'][] = $image->getJsonData();
    }
    return $data;
  }

  public function __toString()
  {
    return json_encode($this->getJsonData());
  }
}
?>
