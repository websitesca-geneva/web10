<?
namespace Web10\Web\Blocks\Image;

use Web10\Domain\Blocks\Image;
use Web10\Web\Blocks\BaseView;

class View extends BaseView
{
  public function getHTML(Image $imageBlock)
  {
    $image = $imageBlock->getImage();
    if ($image != null)
    {
      $src = $image->getRelativePath(500);
    }
    else
    {
      $src = $imageBlock->getDefaultSrc();
    }
    $html = "<img border='0' src='$src' />";
    return $html;
  }
}
?>
