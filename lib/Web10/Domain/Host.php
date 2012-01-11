<?
namespace Web10\Domain;

/** @Entity @Table(name="host", uniqueConstraints={@UniqueConstraint(name="hostname_idx", columns={"hostname"})}) */
class Host
{
  /** @Id @Column(type="integer") @GeneratedValue */
  protected $id;
  public function getId() { return $this->id; }
  public function setId($id) { $this->id = $id; }

  /** @Column(type="string") */
  protected $hostname;
  public function getHostname() { return $this->hostname; }
  public function setHostname($val) { $this->hostname = $val; }

  /** @ManyToOne(targetEntity="Website", inversedBy="hosts", cascade={"persist"}) */
  protected $website;
  public function getWebsite() { return $this->website; }
  public function setWebsite($val) { $this->website = $val; }

  public function __construct()
  {
  }
}
?>
