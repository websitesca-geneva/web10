<?
namespace Web10\Domain;

/**
 @Entity @Table(
 name="visitor",
 uniqueConstraints={@UniqueConstraint(name="UNIQ_sessionId", columns={"sessionId"})},
 indexes={@Index(name="INDEX_account", columns={"account_id"})}
 )
 */
class Visitor
{
  /** @Id @Column(type="integer") @GeneratedValue */
  protected $id;
  public function getId() { return $this->id; }
  public function setId($id) { $this->id = $id; }

  /** @Column(type="string") */
  protected $sessionId;
  public function getSessionId() { return $this->sessionId; }
  public function setSessionId($id) { $this->sessionId = $id; }

  /** @ManyToOne(targetEntity="Account") */
  protected $account;
  public function getAccount() { return $this->account; }
  public function setAccount($val) { $this->account = $val; }

  /** @Column(type="boolean") */
  protected $isAuthenticated;
  public function getIsAuthenticated() { return $this->isAuthenticated; }
  public function setIsAuthenticated($is) { $this->isAuthenticated = $is; }

  public function __construct()
  {
    $this->setIsAuthenticated(false);
  }
}
?>
