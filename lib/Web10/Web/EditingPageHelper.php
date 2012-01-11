<?
namespace Web10\Web;

class EditingPageHelper
{
  public function __construct()
  {
  }

  public function getHeader($title)
  {
    $html  = "<html>";
    $html .= "<head><title>$title</title></head>";
    $html .= "<body>";
    $html .= "<h1>Websites.ca</h1>";
    $html .= "<p>This is the master or template for the editing pages.";
    return $html;
  }

  public function getFooter()
  {
    $html  = "</body>";
    $html .= "</html>";
    return $html;
  }
}
?>
