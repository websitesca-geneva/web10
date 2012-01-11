<?
namespace Web10\Web\Blocks\Container;

use Web10\Web\DialogTabbed;
use Web10\Domain\Blocks\Container;

class Edit extends DialogTabbed
{
  protected $id;
  protected $container;

  public function __construct($name, $scope, $id, Container $text)
  {
    $params = array(
      "name" => $name,
      "scope" => $scope,
      "id" => $id
    );
    parent::__construct($params, '/controller/Container/update');
    $this->text = $text;
    $this->title = "Edit Container";

    $this->addTab("Main", $this->mainTab());
  }

  public function mainTab()
  {
    $id = $this->params['id'];
    $name = $this->params['name'];
    $html = "Property 1:
      <p><input type='text' name='property1' id='property1'></p>";
    return $html;
  }
}
?>
