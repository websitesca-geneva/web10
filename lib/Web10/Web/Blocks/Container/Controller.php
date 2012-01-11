<?
namespace Web10\Web\Blocks\Container;

use Web10\Repository\Block\BlockRepo;
use Web10\Web\Blocks\ContainerContext;

class Controller extends Web10\Web\Blocks\BlockController
{
  protected $blockRepo;
  protected $container;

  public function __construct($name, $scope='PAGE', $containerContext=null)
  {
    parent::__construct($name, $scope, $containerContext);

    //first load data entities
    $this->blockRepo = new BlockRepo($this->context->getEntityManager(), 'Domain\Blocks\Container');

    $this->context->addScript("/Web/Blocks/Container/container.js");

    //TODO: install if text is null
  }

  protected function get_class_name($object = null)
  {
    if (!is_object($object) && !is_string($object))
    {
      return false;
    }

    $class = explode('\\', (is_string($object) ? $object : get_class($object)));
    return $class[count($class) - 1];
  }

  public function edit($id)
  {
    $this->container = $this->blockRepo->getBlockById($this->context->getWebsite(), $id);

    $edit = new Edit($this->name, $this->scope, $id, $this->container);
    return $edit->render();
  }

  public function insert($id, $selectedType)
  {
    $this->container = $this->blockRepo->getBlockById($this->context->getWebsite(), $id);

    //make new block of type $selectedType
    $count = $this->container->getBlockCount();
    $count++;
    $cls = "\\Domain\\Blocks\\$selectedType";
    $block = new $cls();
    $block->setName("$selectedType" . uniqid());
    $block->setContainerScope($this->context->getWebsite(), $this->container);

    $this->container->addBlock($block);
    $this->blockRepo->save($this->container);
  }

  public function add($id)
  {
    $this->container = $this->blockRepo->getBlockById($this->context->getWebsite(), $id);

    $add = new Add($this->name, $this->scope, $id, $this->container);
    return $add->render();
  }

  public function view()
  {
    $this->container = $this->blockRepo->getBlock(
    $this->context->getWebsite(),
    $this->context->getLayout(),
    $this->context->getPage(),
    $this->scope, $this->name,
    $this->getContainer());

    if ($this->containerContext == null)
    $this->containerContext = new ContainerContext($this->container);
    $this->containerContext->push($this->name);

    $id = $this->container->getId();

    $html  = "<div class='block' id='block$id'>";
    foreach ($this->container->getBlocks() as $block)
    {
      $blockCls = $this->get_class_name($block);
      require_once("Web/Blocks/$blockCls/Controller.php");
      $controllerCls = "\\Web\\Blocks\\$blockCls\\Controller";
      $controller = new $controllerCls($block->getName(), $block->getScope(), $this->containerContext);
      $html .= $controller->view();
    }
    $html .= "</div>";

    return $this->wrapContainer($html,
      '/controller/Container/edit',
      '/controller/Container/add',
    array('id'=>$id));
  }
}
?>
