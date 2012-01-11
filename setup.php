<?
print "SETUP, include_path: " . get_include_path() . "\n";

require_once('Web10/Common/CoreClassLoader.php');
$loader = new Web10\Common\CoreClassLoader();
$loader->setup();

$hosts = array_slice($argv, 1, count($argv)-1);
if (count($hosts) == 0)
  array_unshift($hosts, 'localhost');

print "SETUP, ADDING HOSTS: " . implode($hosts, ' ') . "\n";

use Doctrine\ORM\Tools\SchemaTool;
//use Web10\Common\InjectorWrapper;
//use DependencyInjection\Context;
use Web10\Common\CoreContainer;

//$inj = InjectorWrapper::getStatic();
//$inj->setupCommandline();
$cont = CoreContainer::getStatic();

$websiteDefRepo = $cont->get('Web10\Repository\WebsiteDefRepo');
$accountRepo = $cont->get('Web10\Repository\AccountRepo');
$pageRepo = $cont->get('Web10\Repository\PageRepo');
$websiteRepo = $cont->get('Web10\Repository\WebsiteRepo');
$hostRepo = $cont->get('Web10\Repository\HostRepo');
$blockRepo = $cont->get('Web10\Repository\Block\BlockRepo');

$em = $cont->get('Doctrine\ORM\EntityManager');

$tool = new SchemaTool($em);

$tool->dropDatabase();

$classes = array(
	$em->getClassMetadata('Web10\Domain\Website'),
	$em->getClassMetadata('Web10\Domain\Page'),
	$em->getClassMetadata('Web10\Domain\Url'),
	$em->getClassMetadata('Web10\Domain\Host'),
	$em->getClassMetadata('Web10\Domain\Visitor'),
	$em->getClassMetadata('Web10\Domain\Account'),
	$em->getClassMetadata('Web10\Domain\Blocks\Block'),
	$em->getClassMetadata('Web10\Domain\Blocks\Text'),
	$em->getClassMetadata('Web10\Domain\Blocks\Container'),
	$em->getClassMetadata('Web10\Domain\Folder'),
	$em->getClassMetadata('Web10\Domain\File'),
	$em->getClassMetadata('Web10\Domain\Image'),
	$em->getClassMetadata('Web10\Domain\Blocks\Menu'),
	$em->getClassMetadata('Web10\Domain\Blocks\Image'),
	$em->getClassMetadata('Web10\Domain\Blocks\ImageGrid'),
	$em->getClassMetadata('Web10\Domain\Blocks\ImageGrid_Image')
);
$tool->createSchema($classes);

//now build the test data

//ACCOUNT
$account = new Web10\Domain\Account();
$account->setEmail("sborland@gmail.com");
$account->setPassword("test");
$accountRepo->saveAndFlush($account);

//WEBSITE
$website = new Web10\Domain\Website();
$website->setAccount($account);
$website->setWebsiteDef('winnipeg');
$account->addWebsite($website);
$websiteRepo->saveAndFlush($website);

//$inj->register(new Context('account'))->setValue($account);
//$inj->register(new Context('website'))->setValue($website);

//$ac = $cont->get('Web10\Common\Contexts\AccountContext');
//$ac->initWebsiteByWebsite($website);
//$inj->setAccountContext($ac);

$ac = $cont->get('Web10\Common\Contexts\AccountContext');
$ac->setupByWebsite($website);

//PAGE
$page = new Web10\Domain\Page();
$page->setName('Home');
$page->setTitle("Welcome to Test Co. Ltd. in Winnipeg, Manitoba");
$website->addPage($page);
$page->setWebsite($website);
$page->setLayout("main");

//URL
$url = new Web10\Domain\Url();
$url->setUrl("/");
$url->setPage($page);
$url->setWebsite($website);
$page->addUrl($url);

$pageRepo->saveAndFlush($page);

//
//ABOUT PAGE
//
$p = new Web10\Domain\Page();
$p->setName('About');
$p->setTitle("About Test Co. Ltd.");
$website->addPage($p);
$p->setWebsite($website);
$u = new Web10\Domain\Url();
$u->setUrl("/about");
$u->setPage($p);
$u->setWebsite($website);
$p->addUrl($u);
$p->setLayout("main");

$pageRepo->saveAndFlush($p);
$websiteRepo->saveAndFlush($website);

//PRODUCTS PAGE
$productsPage = new Web10\Domain\Page();
$productsPage->setName('Products');
$productsPage->setTitle("Our Product Line");
$website->addPage($productsPage);
$productsPage->setWebsite($website);
$u = new Web10\Domain\Url();
$u->setUrl("/products");
$u->setPage($productsPage);
$u->setWebsite($website);
$productsPage->addUrl($u);
$productsPage->setLayout("main");

$pageRepo->saveAndFlush($p);
$websiteRepo->saveAndFlush($website);


//WIDGETS PAGE
$p = new Web10\Domain\Page();
$p->setName('Widgets');
$p->setTitle("Products > Widgets");
$website->addPage($p);
$p->setWebsite($website);
$u = new Web10\Domain\Url();
$u->setUrl("/products/widgets");
$u->setPage($p);
$u->setWebsite($website);
$p->addUrl($u);
$p->setLayout("main");
$p->setParent($productsPage);
$productsPage->addSubpage($p);

$pageRepo->saveAndFlush($p);
$websiteRepo->saveAndFlush($website);

//BIDGETS PAGE
$p = new Web10\Domain\Page();
$p->setName('Bidgets');
$p->setTitle("Products > Bidgets");
$website->addPage($p);
$p->setWebsite($website);
$u = new Web10\Domain\Url();
$u->setUrl("/products/bidgets");
$u->setPage($p);
$u->setWebsite($website);
$p->addUrl($u);
$p->setLayout("main");
$p->setParent($productsPage);
$productsPage->addSubpage($p);

$pageRepo->saveAndFlush($p);
$websiteRepo->saveAndFlush($website);

//
// HOST
//
function addHost($website, $hostname, $hostRepo)
{
  $host = new Web10\Domain\Host();
  $host->setHostname($hostname);
  $host->setWebsite($website);
  $website->addHost($host);
  $website->setDefaultHost($host);
  $hostRepo->saveAndFlush($host);
}
foreach ($hosts as $host)
{
  addHost($website, $host, $hostRepo);
}

$block = new Web10\Domain\Blocks\Text();
$block->setText("Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse libero eros, pulvinar ut fringilla ac, porttitor faucibus augue. Pellentesque vitae mi justo. Maecenas ornare felis a mi aliquet faucibus. Donec non dui metus, in fringilla neque. Aenean metus augue, vestibulum sed fringilla sed, ultrices quis libero. Morbi sed dolor leo, sagittis pulvinar lorem. Ut tincidunt, metus a porttitor mattis, elit risus varius nunc, at fermentum leo leo ac lectus.");
$block->setName("Right Sidebar Text");
$block->setScope("LAYOUT");
$block->setLayout("main");
$block->setWebsite($website);
$blockRepo->saveAndFlush($block);

$container = new Web10\Domain\Blocks\Container();
$container->setName("Main Container");
$container->setPageScope($website, $page);
$cblock = new Web10\Domain\Blocks\Text();
$cblock->setText("This is some block text from block1 inside the container.");
$cblock->setName("block1");
$cblock->setContainerScope($website, $container);
$container->addBlock($cblock);
$blockRepo->saveAndFlush($container);

//IMAGES PAGE
$p = new Web10\Domain\Page();
$p->setName('Images');
$p->setTitle("Images of our Restaurant");
$website->addPage($p);
$p->setWebsite($website);
$u = new Web10\Domain\Url();
$u->setUrl("/images");
$u->setPage($p);
$u->setWebsite($website);
$p->addUrl($u);
$p->setLayout("images");


//SETUP DIRECTORIES
//$accountManager = $cont->get('Web10\Business\AccountManager');
//$accountManager->setupDataDirectory();
$deploymentHelper = $cont->get('Web10\Business\DeploymentHelper');
$deploymentHelper->setupDataDirectory($account, $website, true, true);

//IMAGE
$um = $cont->get('Web10\Business\UploadManager');
$um->storeImage('map', $cont['rootpathweb'].'/img/map.jpg', 'jpg', false);
$um->storeImage('explosion', $cont['rootpathweb'].'/img/explosion.jpg', 'jpg', false);



?>
