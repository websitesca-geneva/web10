<?
namespace Web10\Domain;

use Doctrine\Common\Collections\ArrayCollection;
use Web10\Common\JsonEntity;

/** @Entity @Table(name="page") */
class Page implements JsonEntity
{
  /** @Id @Column(type="integer") @GeneratedValue */
  protected $id;
  public function getId() { return $this->id; }
  public function setId($id) { $this->id = $id; }

  /** @Column(type="string") */
  protected $name;
  public function getName() { return $this->name; }
  public function setName($name) { $this->name = $name; }

  /** @Column(type="string") */
  protected $title;
  public function getTitle() { return $this->title; }
  public function setTitle($title) { $this->title = $title; }

  /** @ManyToOne(targetEntity="Website", inversedBy="pages", cascade={"persist"}) */
  protected $website;
  public function getWebsite() { return $this->website; }
  public function setWebsite($val) { $this->website = $val; }

  /** @OneToMany(targetEntity="Url", mappedBy="page", cascade={"persist", "remove", "merge", "detach"}) */
  protected $urls;
  public function getUrls() { return $this->urls; }
  public function setUrls($val) { $this->urls = $val; }
  public function addUrl(Url $url)
  {
    $this->urls[] = $url;
    $this->setDefaultUrl($url);
  }

  /** @OneToOne(targetEntity="Url", cascade={"persist", "remove", "merge", "detach"})
   * @JoinColumn(name="default_url_id", referencedColumnName="id", nullable="true", onDelete="SET NULL") */
  protected $defaultUrl;
  public function getDefaultUrl() { return $this->defaultUrl; }
  public function setDefaultUrl(Url $url) { $this->defaultUrl = $url; }

  /** @ManyToOne(targetEntity="Page", inversedBy="subpages") */
  protected $parent;
  public function getParent() { return $this->parent; }
  public function setParent($val) { $this->parent = $val; }

  /** @OneToMany(targetEntity="Page", mappedBy="parent", cascade={"persist"}) */
  protected $subpages;
  public function getSubpages() { return $this->subpages; }
  public function setSubpages($val) { $this->subpages = $val; }
  public function addSubpage($p) { $this->subpages[] = $p; }
  public function clearSubpages() { $this->subpages = new ArrayCollection(); }
  public function removeSubpage(Page $p) { $this->subpages->removeElement($p); }

  /** @Column(type="string") */
  protected $layout;
  public function getLayout() { return $this->layout; }
  public function setLayout($val) { $this->layout = $val; }

  /** @Column(type="integer", nullable="true") */
  protected $ordering;
  public function getOrdering() { return $this->ordering; }
  public function setOrdering($val) { $this->ordering = $val; }

  public function __construct()
  {
  }

  public function getJsonData()
  {
    $data = array();
    $data['id'] = $this->id;
    $data['name'] = $this->name;
    $data['title'] = $this->title;
    $data['websiteId'] = $this->website->getId();
    $data['defaultUrl'] = $this->defaultUrl->getUrl();
    if ($this->parent)
    $data['parentPageId'] = $this->parent->getId();
    else
    $data['parentPageId'] = 0;
    $data['layout'] = $this->layout;
    $data['ordering'] = $this->ordering;
    return $data;
  }

  public function __toString()
  {
    return json_encode($this->getJsonData());
  }
}
?>
