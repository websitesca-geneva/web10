<?
namespace Web10\Web;

class ControllerResponse
{
  protected $code;
  protected $message;
  protected $data;
  protected $extra;

  public function __construct($code, $data=null, $message=null, $extra=null)
  {
    $this->code = $code; //OK or ERROR
    $this->message = $message;
    $this->data = $data;
    $this->extra = $extra;
  }

  public function setData($data)
  {
    $this->data = $data;
  }

  public function setMessage($msg)
  {
    $this->message = $msg;
  }

  public function setExtra($extra)
  {
    $this->extra = $extra;
  }

  public function __toString()
  {
    $data = array();
    $data['code'] = $this->code;
    $data['message'] = $this->message;
    $data['data'] = $this->data;
    $data['extra'] = $this->extra;
    return json_encode($data);
  }
}
?>
