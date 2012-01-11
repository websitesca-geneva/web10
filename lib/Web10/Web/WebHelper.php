<?
namespace Web10\Web;

use Web10\Domain\Page;

class WebHelper
{
  public function __construct()
  {
  }

  public static function redirect($url)
  {
    header("Location: $url");
  }

  public function getRelativeLink(Page $page)
  {
    //TODO: support multilang here
    $name = strtolower($page->getName());
    if ($name == 'home')
    $name = '';
    return "/$name";
  }
}
?>
