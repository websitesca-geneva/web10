<?
namespace Web10\Domain;

use Doctrine\Common\Collections\ArrayCollection;

/** @Entity @Table(name="websitedef") */
class WebsiteDef
{
  /** @Id @Column(type="integer") @GeneratedValue */
  protected $id;
  public function getId() { return $this->id; }
  public function setId($id) { $this->id = $id; }

  /** @OneToMany(targetEntity="Layout", mappedBy="websitedef", cascade={"persist", "remove"}) */
  protected $layouts;
  public function getLayouts() { return $this->layouts; }
  public function setLayouts($val) { $this->layouts = $val; }
  public function addLayout($val) { $this->layouts[] = $val; }

  /** @Column(type="string") */
  protected $name;
  public function getName() { return $this->name; }
  public function setName($name) { $this->name = $name; }

  /** @OneToMany(targetEntity="Website", mappedBy="websitedef", cascade={"persist"}) */
  protected $websites;
  public function getWebsites() { return $this->websites; }
  public function setWebsites($val) { $this->websites = $val; }
  public function addWebsite($val) { $this->websites[] = $val; }

  public function __construct()
  {
    $this->layouts = new ArrayCollection();
    $this->websites = new ArrayCollection();
  }
}
?>
