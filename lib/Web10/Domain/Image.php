<?
namespace Web10\Domain;

use Doctrine\Common\Collections\ArrayCollection;
use \InvalidArgumentException;
use Web10\Common\JsonEntity;

/** @Entity @Table(name="image") */
class Image extends File implements JsonEntity
{
  /** @Id @Column(type="integer") @GeneratedValue */
  protected $id;
  public function getId() { return $this->id; }
  public function setId($id) { $this->id = $id; }

  /** @Column(type="string") */
  protected $caption;
  public function getCaption() { return $this->caption; }
  public function setCaption($caption) { $this->caption = $caption; }

  /** @Column(type="integer") */
  protected $width;
  public function getWidth() { return $this->width; }
  public function setWidth($x) { $this->width = $x; }

  /** @Column(type="integer") */
  protected $height;
  public function getHeight() { return $this->height; }
  public function setHeight($x) { $this->height = $x; }

  public function getJsonData()
  {
    $data = parent::getJsonData();
    $data['caption'] = $this->getCaption();
    $data['width'] = $this->getWidth();
    $data['height'] = $this->getHeight();
    return $data;
  }

  public function __toString()
  {
    return json_encode($this->getJsonData());
  }

  public function getRelativePath($sizeCode)
  {
    $validSizeCodes = array(100, 200, 300, 500, 700, 900, 1200, 'ORIG');
    if (! in_array($sizeCode, $validSizeCodes))
    	throw new InvalidArgumentException("$sizeCode is not a valid sizeCode.");
    $accountId = $this->website->getAccount()->getId();
    $websiteId = $this->website->getId();
    $imageId = $this->id;
    $ext = $this->ext;
    return "/data/account_{$accountId}/website_{$websiteId}/image_{$imageId}_{$sizeCode}.{$ext}";
  }

  public function __construct()
  {
  }
}
?>
