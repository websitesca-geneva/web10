<?
namespace Web10\Editing\Pages;

use Web10\Business\PageManager;
use Web10\Web\FormHelper;
use Web10\Web\DialogTab;

class EditPageTab extends DialogTab
{
  protected $pageManager;
  protected $pageId;

  public function __construct(PageManager $pageManager, $pageId)
  {
    $this->pageManager = $pageManager;
    $this->pageId = $pageId;
    parent::__construct('Edit Page');
  }

  public function getBody()
  {
    $layouts = $this->pageManager->getAllLayouts();
    $helper = new FormHelper();
    $layoutsArr = $helper->buildNameValuePairs($layouts, 'getName', 'getId');

    $page = $this->pageManager->getPage($this->pageId);
    $host = $this->pageManager->getDefaultHost();
    $url = substr($page->getDefaultUrl()->getUrl(), 1);

    $html  = $helper->formStart("/Editing/Pages/savePage", 'savePageForm', array('pageId'=>$this->pageId));
    $html .= "<table class='formTable'>";
    $html .= "<tr><td>Page Name</td><td>" . $helper->inputText('name', $page->getName()) . "</td></tr>";
    $html .= "<tr><td>Page Title</td><td>" . $helper->inputText('title', $page->getTitle()) . "</td></tr>";
    $html .= "<tr><td>Layout</td><td>" . $helper->inputSelect('layoutId', $layoutsArr, $page->getLayout()->getId()) . "</td></tr>";
    $html .= "<tr><td>Link will be</td><td>http://$host/<span name='url' style='font-weight: bolder;'>$url</span></td></tr>";
    $html .= "<tr><td colspan='2'>
      <a href='javascript:void(0);' onclick='javascript:PageManager_savePageClick(this);'>Save Page</a> / 
      <a href='javascript:void(0);' onclick='javascript:PageManager_deletePageClick(this, {$this->pageId});'>Delete Page</a>
      </td></tr>";
    $html .= "</table>";
    $html .= $helper->formEnd();
    return $html;
  }
}
?>
