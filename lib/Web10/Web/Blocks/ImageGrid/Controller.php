<?
namespace Web10\Web\Blocks\ImageGrid;

use Web10\Business\ImageManager;
use Web10\Business\ImageGridBlockManager;
use Web10\Common\Contexts\BlockContext;
use Web10\Common\Contexts\VisitorContext;
use Web10\Domain\Blocks\ImageGrid;
use Web10\Domain\Blocks\ImageGrid_Image;
use \InvalidArgumentException;
use Web10\Common\ViewingAssets;
use AssetManager\CssAsset;
use AssetManager\JsAsset;

class Controller
{
  protected $bc;
  protected $vc;
  protected $im;
  protected $manager;

  public function __construct(BlockContext $bc, VisitorContext $vc, ImageGridBlockManager $manager, ImageManager $im, ViewingAssets $assets)
  {
    $this->bc = $bc;
    $this->vc = $vc;
    $this->im = $im;
    $this->manager = $manager;
    $this->assets = $assets;
  }

  protected function addViewAssets()
  {
    //add required assets: a css file for the imagegrid and a js file to do lightbox
    $this->assets->addAsset(new CssAsset('/css/Web10/Web/Blocks/ImageGrid.css'));
    $this->assets->addAsset(new JsAsset('/js/jquery.js'));
    $this->assets->addFileAssets('/asset/fancybox/jquery.fancybox.*');
    $this->assets->addAsset(new JsAsset('/js/Web10/Block/ImageGrid/ImageGrid.js'));
  }
  
  public function view($isPartial=false)
  {
    $this->addViewAssets();
    $view = new View($this->bc, $this->vc, $this->manager);
    return $view->render(!$isPartial, $this->manager);
  }
  
  public function update($data, $id)
  {
    $imageGrid = $this->manager->getImageGridBlock($id);
    $this->manager->clearImages($imageGrid);
    $this->manager->saveImageGridBlock($imageGrid);
    
    $count = 0;
    foreach ($data->images as $img)
    {
      $image = $this->im->getImage($img->id);
      $i = new ImageGrid_Image();
      $i->setImageGrid($imageGrid);
      $i->setImage($image);
      $i->setOrdering($count+=10);
      $imageGrid->addImage($i);
    }
    
    $this->manager->saveImageGridBlock($imageGrid);
    
    return $imageGrid;
  }
}
?>
