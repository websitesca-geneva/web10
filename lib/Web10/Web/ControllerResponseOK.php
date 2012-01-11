<?
namespace Web10\Web;

class ControllerResponseOK extends ControllerResponse
{
  public function __construct($data, $msg=null, $extra=null)
  {
    parent::__construct('OK', "$data", $msg, $extra);
  }
}
?>
