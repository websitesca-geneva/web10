<?
namespace Web10\Web\Blocks\ImageGrid;

use Web10\Common\Contexts\BlockContext;
use Web10\Common\Contexts\VisitorContext;
use Web10\Domain\Blocks\ImageGrid;
use Web10\Web\Blocks\BaseView;
use Web10\Business\ImageGridBlockManager;

class View extends BaseView
{
  public function __construct(BlockContext $bc, VisitorContext $vc, ImageGridBlockManager $manager)
  {
    parent::__construct($bc, $vc);
    $this->manager = $manager;
  }
  
  public function getHTML(ImageGrid $imageGrid)
  {
  	$html = "\n<div>";
  	$images = $imageGrid->getImages();
  	if ($images->count() > 0)
  	{
	    foreach ($images as $img)
	    {
	    	$small = $img->getImage()->getRelativePath($imageGrid->getSizeCode());
	    	$big = $img->getImage()->getRelativePath(900);
	    	$html .= "<div class='imageWrapper' style='height: {$imageGrid->getSizeCode()}px; width:{$imageGrid->getSizeCode()}px;'>
	    		<a class='fancybox' rel='group{$imageGrid->getId()}' href='$big'><img src='$small' border='0' /></a>
	    		</div>";
	    }
  	}
  	else 
  	{
  		$html .= "Image Grid Empty."; //TODO: translate
  	}
    $html .= "\n</div>";
    return $html;
  }
}
?>
