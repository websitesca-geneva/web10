<?
namespace Web10\Domain;

use Doctrine\Common\Collections\ArrayCollection;

/** @Entity @Table(name="website") */
class Website
{
  /** @Id @Column(type="integer") @GeneratedValue */
  protected $id;
  public function getId() { return $this->id; }
  public function setId($id) { $this->id = $id; }

  /** @OneToMany(targetEntity="Page", mappedBy="website", cascade={"all"}) */
  protected $pages;
  public function getPages() { return $this->pages; }
  public function setPages($val) { $this->pages = $val; }
  public function addPage($page) { $this->pages[] = $page; }

  /** @OneToMany(targetEntity="Url", mappedBy="website", cascade={"all"}) */
  protected $urls;
  public function getUrls() { return $this->urls; }
  public function setUrls($val) { $this->urls = $val; }
  public function addUrl($url) { $this->urls[] = $url; }
  
  /** @Column(type="string", nullable="true") */
  protected $websiteDef;
  public function getWebsiteDef() { return $this->websiteDef; }
  public function setWebsiteDef($v) { $this->websiteDef = (empty($v) ? null : $v); }
  
  /** @OneToMany(targetEntity="Host", mappedBy="website", cascade={"all"}) */
  protected $hosts;
  public function getHosts() { return $this->hosts; }
  public function setHosts($val) { $this->hosts = $val; }
  public function addHost($host) { $this->hosts[] = $host; }

  /** @OneToOne(targetEntity="Host") @JoinColumn(name="default_host_id", referencedColumnName="id") */
  protected $defaultHost;
  public function getDefaultHost() { return $this->defaultHost; }
  public function setDefaultHost($val) { $this->defaultHost = $val; }

  /** @ManyToOne(targetEntity="Account", inversedBy="websites", cascade={"all"}) */
  protected $account;
  public function getAccount() { return $this->account; }
  public function setAccount($a) { $this->account = $a; }

  public function __construct()
  {
    $this->pages = new ArrayCollection();
    $this->urls = new ArrayCollection();
    $this->hosts = new ArrayCollection();
  }
}
?>
