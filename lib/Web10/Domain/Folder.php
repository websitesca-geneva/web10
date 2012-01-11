<?
namespace Web10\Domain;

use Doctrine\Common\Collections\ArrayCollection;
use Web10\Common\JsonEntity;

/** @Entity @Table(name="folder") */
class Folder implements JsonEntity
{
  /** @Id @Column(type="integer") @GeneratedValue */
  protected $id;
  public function getId() { return $this->id; }
  public function setId($id) { $this->id = $id; }

  /** @Column(type="string") */
  protected $name;
  public function getName() { return $this->name; }
  public function setName($name) { $this->name = $name; }

  /** @ManyToOne(targetEntity="Website", inversedBy="pages") */
  protected $website;
  public function getWebsite() { return $this->website; }
  public function setWebsite($val) { $this->website = $val; }

  /** @ManyToOne(targetEntity="Folder", inversedBy="subfolders") */
  protected $parent;
  public function getParent() { return $this->parent; }
  public function setParent($val) { $this->parent = $val; }

  /** @OneToMany(targetEntity="Folder", mappedBy="parent", cascade={"all"}) */
  protected $subfolders;
  public function getSubfolders() { return $this->subfolders; }
  public function addSubfolder($f) { $this->subfolders[] = $f; }

  /** @OneToMany(targetEntity="File", mappedBy="folder", cascade={"all"}) */
  protected $files;
  public function getFiles() { return $this->files; }
  public function addFile($f) { $this->files[] = $f; }

  public function __construct()
  {
    $this->subfolders = new ArrayCollection();
    $this->files = new ArrayCollection();
  }

  public function getJsonData()
  {
    $data = array();
    $data['id'] = $this->getId();
    $data['name'] = $this->getName();
    $data['websiteId'] = $this->website->getId();
    if ($this->parent)
    $data['parentFolderId'] = $this->parent->getId();
    $data['hasSubfolders'] = $this->subfolders->count();
    $data['hasFiles'] = $this->files->count();
    return $data;
  }

  public function __toString()
  {
    return json_encode($this->getJsonData());
  }
}
?>
