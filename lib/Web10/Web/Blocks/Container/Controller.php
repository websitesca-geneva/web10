<?
namespace Web10\Web\Blocks\Container;

use Web10\Common\Contexts\BlockContext;
use Web10\Common\Contexts\VisitorContext;
use \InvalidArgumentException;

class Controller
{
  protected $bc;
  protected $vc;
  protected $manager;

  public function __construct(BlockContext $bc, VisitorContext $vc)
  {
    $this->bc = $bc;
    $this->vc = $vc;
  }
  
  protected function render($wrap=true)
  { 
    $blockType = $this->block->getBlockType();//$this->get_class_name($this->block);
    $blockId = $this->block->getId();
    $html = "";

    if ($wrap)
    {
      $html .= "<div class='block-wrapper'>";
      if ($this->visitor->getIsAuthenticated())
      {
        $html .= "<div class='block-menu'><a href='javascript:void(0);' name='edit'>Edit</a></div>";
      }
    }

    $html .= "<div class='block $blockType' blockid='$blockId'>";
    $html .= $this->getHTML($this->block);
    $html .= "</div>";

    if ($wrap)
    {
      $html .= "</div>";
    }

    return $html;
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
