<?
namespace Web10\Domain\Blocks;

use Web10\Common\JsonEntity;

/** @Entity @Table(name="block_text") */
class Text extends Block implements JsonEntity
{
  /** @Id @Column(type="integer") @GeneratedValue */
  protected $id;
  public function getId() { return $this->id; }
  public function setId($id) { $this->id = $id; }

  /** @Column(type="text") */
  protected $text;
  public function getText() { return $this->text; }
  public function setText($val) { $this->text = $val; }

  public function __construct()
  {
    parent::__construct();
    $this->text = "Text goes here.";
  }

  public function getJsonData()
  {
    $data = parent::getJsonDataBase();
    $data['text'] = $this->getText();
    return $data;
  }

  public function __toString()
  {
    return json_encode($this->getJsonData());
  }
}
?>
