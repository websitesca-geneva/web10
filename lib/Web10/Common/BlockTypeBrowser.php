<?
namespace Web10\Common;

class BlockTypeBrowser
{
  protected $data;

  public function __construct()
  {
    $this->data = array(
      "Basic" => array(
        "Text"
        ),
      "Narrow" => array(
        "Text"
        )
        );
  }

  public function getAllTypes()
  {
    return array_unique($this->getAllTypesR($this->data));
  }

  private function getAllTypesR($a)
  {
    $types = array();
    foreach ($a as $val)
    {
      if (is_array($val))
      $types = array_merge($types, $this->getAllTypesR($val));
      else
      $types[] = $val;
    }
    return $types;
  }

  public function getGroups()
  {
    return array_keys($this->data);
  }

  public function getTypesForGroup($group)
  {
    return array_keys($this->data[$group]);
  }
}
?>
