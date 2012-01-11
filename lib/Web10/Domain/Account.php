<?
namespace Web10\Domain;

use Doctrine\Common\Collections\ArrayCollection;

/** @Entity @Table(name="account", uniqueConstraints={@UniqueConstraint(name="email_idx", columns={"email"})}) */
class Account
{
  /** @Id @Column(type="integer") @GeneratedValue */
  protected $id;
  public function getId() { return $this->id; }
  public function setId($id) { $this->id = $id; }

  /** @Column(type="string", unique="true") */
  protected $email;
  public function getEmail() { return $this->email; }
  public function setEmail($email) { $this->email = $email; }

  /** @Column(type="string") */
  protected $password;
  public function getPassword() { return $this->password; }
  public function setPassword($pw) { $this->password = $pw; }

  /** @OneToMany(targetEntity="Website", mappedBy="account", cascade={"all"}) */
  protected $websites;
  public function getWebsites() { return $this->websites; }
  public function setWebsites($val) { $this->websites = $val; }
  public function addWebsite($website) { $this->websites[] = $website; }

  public function __construct()
  {
    $this->websites = new ArrayCollection();
  }
}
?>
