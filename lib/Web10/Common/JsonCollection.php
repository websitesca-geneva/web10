<?php
namespace Web10\Common;

use Web10\Common\JsonEntity;

class JsonCollection implements JsonEntity
{
  protected $items;

  public function __construct($items=array())
  {
    $this->items = $items;
  }

  public function add($item)
  {
    $this->items[] = $item;
  }

  public function getJsonData()
  {
    $data = array();
    foreach ($this->items as $item)
    {
      if ($item instanceof JsonEntity)
      $data[] = $item->getJsonData();
      else
      $data[] = $item;
    }
    return $data;
  }

  public function __toString()
  {
    return json_encode($this->getJsonData());
  }
}
?>
