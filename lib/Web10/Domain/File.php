<?
namespace Web10\Domain;

use Doctrine\Common\Collections\ArrayCollection;
use Web10\Common\JsonEntity;

/**
 * @Entity
 * @Table(name="file")
 * @InheritanceType("SINGLE_TABLE")
 * @DiscriminatorColumn(name="discriminator", type="string")
 * @DiscriminatorMap({"FILE" = "File", "IMAGE" = "Image"})
 */
class File implements JsonEntity
{
  /** @Id @Column(type="integer") @GeneratedValue */
  protected $id;
  public function getId() { return $this->id; }
  public function setId($id) { $this->id = $id; }

  /** @Column(type="string", nullable="true") */
  protected $name;
  public function getName() { return $this->name; }
  public function setName($name) { $this->name = $name; }

  /** @ManyToOne(targetEntity="Website", inversedBy="pages") */
  protected $website;
  public function getWebsite() { return $this->website; }
  public function setWebsite($val) { $this->website = $val; }

  /** @ManyToOne(targetEntity="Folder", inversedBy="files") */
  protected $folder;
  public function getFolder() { return $this->folder; }
  public function setFolder($val) { $this->folder = $val; }

  /** @Column(type="string") */
  protected $ext;
  public function getExt() { return $this->ext; }
  public function setExt($ext) { $this->ext = $ext; }

  public function __construct()
  {
  }

  public function getJsonData()
  {
    $data = array();
    $data['id'] = $this->getId();
    $data['name'] = $this->getName();
    $data['accountId'] = $this->website->getAccount()->getId();
    $data['websiteId'] = $this->website->getId();
    if ($this->folder)
    $data['folderId'] = $this->folder->getId();
    else
    $data['folderId'] = null;
    $data['ext'] = $this->getExt();
    $cls = substr(get_class($this), strrpos(get_class($this), '\\')+1);
    $data['type'] = $cls;
    return $data;
  }

  public function __toString()
  {
    return json_encode($this->getJsonData());
  }
}
?>
