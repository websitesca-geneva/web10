<?
namespace Web10\Domain;

/** @Entity @Table(
 * name="url",
 * uniqueConstraints={@UniqueConstraint(name="UNIQ_website_url", columns={"website_id","url"})}
 * )
 */
class Url
{
  /** @Id @Column(type="integer") @GeneratedValue */
  protected $id;
  public function getId() { return $this->id; }
  public function setId($id) { $this->id = $id; }

  /** @Column(type="string") */
  protected $url;
  public function getUrl() { return $this->url; }
  public function setUrl($val) { $this->url = $val; }

  /** @ManyToOne(targetEntity="Website", inversedBy="urls", cascade={"persist"}) */
  protected $website;
  public function getWebsite() { return $this->website; }
  public function setWebsite($val) { $this->website = $val; }

  /** @ManyToOne(targetEntity="Page", inversedBy="urls", cascade={"persist", "remove", "merge", "detach"}) */
  protected $page;
  public function getPage() { return $this->page; }
  public function setPage($val) { $this->page = $val; }

  public function __construct()
  {
  }
}
?>
