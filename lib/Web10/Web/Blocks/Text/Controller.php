<?
namespace Web10\Web\Blocks\Text;

use Web10\Common\Contexts\BlockContext;
use Web10\Common\Contexts\VisitorContext;
use Web10\Business\TextBlockManager;
use \InvalidArgumentException;

class Controller
{
  protected $manager;
  protected $bc;
  protected $vc;

  public function __construct(BlockContext $bc, VisitorContext $vc, TextBlockManager $manager)
  {
    $this->bc = $bc;
    $this->vc = $vc;
    $this->manager = $manager;
  }

  public function view($isPartial=false)
  {
    $view = new View($this->bc, $this->vc);
    return $view->render(!$isPartial);
  }

  public function update($data, $id)
  {
    if (empty($data->text))
    {
      throw new InvalidArgumentException("The text cannot be empty.");
    }

    $t = $this->manager->getText($id);
    $t->setText($data->text);
    $this->manager->saveText($t);
    return $t;
  }
}
