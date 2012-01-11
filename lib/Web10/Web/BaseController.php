<?
namespace Web10\Web;

abstract class BaseController
{
  public function __construct()
  {
  }

  protected function buildJsonParams($params)
  {
    $params = json_encode($params);
    return $params;
  }
}
?>
