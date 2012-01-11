<?
namespace Web10\Editing\Pages;

use Web10\Business\PageManager;
use Web10\Web\FormHelper;
use Web10\Web\DialogTab;

class AddPageTab extends DialogTab
{
  protected $pageManager;
  protected $pageId;

  public function __construct(PageManager $pageManager)
  {
    $this->pageManager = $pageManager;
    parent::__construct('Add Page');
  }

  public function getBody()
  {
    $layouts = $this->pageManager->getAllLayouts();
    $helper = new FormHelper();
    $layoutsArr = $helper->buildNameValuePairs($layouts, 'getName', 'getId');

    $host = $this->pageManager->getDefaultHost();
    $html  = $helper->formStart("/Editing/Pages/addPage", 'addPageForm');
    $html .= "<table class='formTable'>";
    $html .= "<tr><td>Page Name</td><td>" . $helper->inputText('name') . "</td></tr>";
    $html .= "<tr><td>Page Title</td><td>" . $helper->inputText('title') . "</td></tr>";
    $html .= "<tr><td>Layout</td><td>" . $helper->inputSelect('layoutId', $layoutsArr) . "</td></tr>";
    $html .= "<tr><td>Link will be</td><td>http://$host/<span name='url' style='font-weight: bolder;'></span></td></tr>";
    $html .= "<tr><td colspan='2'><a href='javascript:void(0);' class='addPageButton'>Add Page</a></td></tr>";
    $html .= "</table>";
    $html .= $helper->formEnd();
    return $html;
  }
}
?>
