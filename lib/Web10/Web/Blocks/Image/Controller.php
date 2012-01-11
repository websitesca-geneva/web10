<?
namespace Web10\Web\Blocks\Image;

use Web10\Business\ImageManager;

use Web10\Business\ImageBlockManager;
use Web10\Common\Contexts\BlockContext;
use Web10\Common\Contexts\VisitorContext;
use \InvalidArgumentException;

class Controller
{
  protected $bc;
  protected $vc;
  protected $im;
  protected $manager;

  public function __construct(BlockContext $bc, VisitorContext $vc, ImageBlockManager $manager, ImageManager $im)
  {
    $this->bc = $bc;
    $this->vc = $vc;
    $this->im = $im;
    $this->manager = $manager;
  }

  public function view($isPartial=false)
  {
    $view = new View($this->bc, $this->vc);
    return $view->render(!$isPartial);
  }

  public function update($data, $id)
  {
    if (empty($data->imageId))
    {
      throw new InvalidArgumentException("You didn't select anything.");
    }

    $image = $this->im->getImage($data->imageId);

    $b = $this->manager->getImageBlock($id);
    $b->setImage($image);

    $this->manager->saveImageBlock($b);
    return $b;
  }
}
?>
