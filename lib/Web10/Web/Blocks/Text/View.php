<?
namespace Web10\Web\Blocks\Text;

use Web10\Domain\Blocks\Text;
use Web10\Web\Blocks\BaseView;

class View extends BaseView
{
  public function getHTML($text)
  {
    $html = $text->getText();
    return $html;
  }
}
?>
