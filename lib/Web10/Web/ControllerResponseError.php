<?
namespace Web10\Web;

class ControllerResponseError extends ControllerResponse
{
  public function __construct($msg)
  {
    parent::__construct("ERROR", null, $msg);
  }
}
?>
