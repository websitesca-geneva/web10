<?
namespace Web10\Web\Blocks\Container;

use Web10\Web\DialogBasic;
use Web10\Domain\Blocks\Container;
use Web10\Common\BlockTypeBrowser;

class Add extends DialogBasic
{
  protected $id;
  protected $container;

  public function __construct($name, $scope, $id, Container $container)
  {
    $params = array(
      "name" => $name,
      "scope" => $scope,
      "id" => $id
    );
    parent::__construct($params, '/controller/Container/insert');
    $this->container = $container;
    $this->title = "Add to this Container";
    $this->subtitle = "Choose the type of data you want to add from the list below.";

    $this->addTab("Main", $this->mainTab());
  }

  public function mainTab()
  {
    $id = $this->params['id'];
    $name = $this->params['name'];
    $browser = new BlockTypeBrowser();
    $types = $browser->getAllTypes();
    $html = "<input type='hidden' name='selectedType' id='selectedType' value=''>";
    $html .= "<p>What type of data?</p>";
    $html .= "<div class='block-types-list'>";
    foreach ($types as $type)
    {
      $html .= "<div class=\"block-type-choice\"><a href=\"javascript:void(0);\" onclick=\"javascript:blockTypeChoiceClick(this, '$type');\">$type</a></div>";
    }
    $html .= "</div>";
    return $html;
  }
}
?>
