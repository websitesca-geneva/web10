<?
namespace Web10\Domain\Blocks;

use Web10\Domain\Website;
use Web10\Domain\Page;
use Web10\Domain\Layout;

/**
 * @Entity @Table(name="block")
 * @InheritanceType("JOINED")
 * @DiscriminatorColumn(name="discriminator", type="string")
 * @DiscriminatorMap({
 *  "container" = "Web10\Domain\Blocks\Container",
 *  "text" = "Web10\Domain\Blocks\Text",
 *  "menu" = "Web10\Domain\Blocks\Menu",
 *  "image" = "Web10\Domain\Blocks\Image",
 *  "imagegrid" = "Web10\Domain\Blocks\ImageGrid"
 * })
 */
class Block
{
  /** @Id @Column(type="integer") @GeneratedValue */
  protected $id;
  public function getId() { return $this->id; }
  public function setId($id) { $this->id = $id; }

  /** @Column(type="string") */
  protected $name;
  public function getName() { return $this->name; }
  public function setName($val) { $this->name = $val; }

  /** @Column(type="string", nullable="true") */
  protected $scope;
  public function getScope() { return $this->scope; }
  public function setScope($val) { $this->scope = $val; }

  /** @ManyToOne(targetEntity="Web10\Domain\Website") */
  protected $website;
  public function getWebsite() { return $this->website; }
  public function setWebsite($val) { $this->website = $val; }

  /** @Column(type="string", nullable="true") */
  protected $layout;
  public function getLayout() { return $this->layout; }
  public function setLayout($val) { $this->layout = $val; }

  /** @ManyToOne(targetEntity="Web10\Domain\Page") */
  protected $page;
  public function getPage() { return $this->page; }
  public function setPage($val) { $this->page = $val; }

  /** @ManyToOne(targetEntity="Container", inversedBy="blocks") */
  protected $container;
  public function getContainer() { return $this->container; }
  public function setContainer($val) { $this->container = $val; }

  public function setLayoutScope(Website $website, Layout $layout)
  {
    $this->setScope("LAYOUT");
    $this->setWebsite($website);
    $this->setLayout($layout);
  }

  public function setPageScope(Website $website, Page $page)
  {
    $this->setScope("PAGE");
    $this->setWebsite($website);
    $this->setPage($page);
  }

  public function setContainerScope(Website $website, Container $container)
  {
    $this->setScope("CONTAINER");
    $this->setWebsite($website);
    $this->setContainer($container);
  }

  public function __construct()
  {
  }

  public function getBlockType()
  {
    $class = explode('\\', get_class($this));
    return $class[count($class)-1];
  }

  protected function getJsonDataBase()
  {
    $data = array();
    $data['id'] = $this->getId();
    $data['scope'] = $this->getScope();
    $data['blocktype'] = $this->getBlockType();
    return $data;
  }
}
?>
