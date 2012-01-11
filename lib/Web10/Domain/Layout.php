<?
namespace Web10\Domain;

use Web10\Common\JsonEntity;

/** @Entity @Table(name="layout") */
class Layout implements JsonEntity
{
  public function getJsonData()
  {
    $data = array();
    $data['id'] = $this->id;
    $data['name'] = $this->name;
    return $data;
  }

  public function __toString()
  {
    return json_encode($this->getJsonData());
  }

  /** @Id @Column(type="integer") @GeneratedValue */
  protected $id;
  public function getId() { return $this->id; }
  public function setId($id) { $this->id = $id; }

  /** @Column(type="string") */
  protected $name;
  public function getName() { return $this->name; }
  public function setName($name) { $this->name = $name; }

  /** @ManyToOne(targetEntity="WebsiteDef", inversedBy="layouts") */
  protected $websitedef;
  public function getWebsiteDef() { return $this->websitedef; }
  public function setWebsiteDef($val) { $this->websitedef = $val; }

  public function __construct()
  {
  }
}
?>
