<?
namespace Web10\Business;

use Web10\Common\ServDep;

class ServiceImplementation
{
  protected $dep;
  protected $time;

  public function __construct(ServDep $dep)
  {
    $this->dep = $dep;
    $this->time = time();
  }

  public function getTime() { return $this->time; }

  public function calculate($x)
  {
    return $this->dep->docalculation($x);
  }
}
?>
