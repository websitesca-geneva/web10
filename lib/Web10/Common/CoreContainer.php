<?php
namespace Web10\Common;

use Smarty;
use Twig_Environment;
use Twig_Loader_Filesystem;

class CoreContainer extends DependencyContainer
{
  protected static $instance = null;

  public function __construct()
  {
    $this->setupCore();
  }

  public static function getStatic()
  {
    if (empty(self::$instance))
    {
      self::$instance = new CoreContainer();
    }
    return self::$instance;
  }
  
  public function setupSAdmin()
  {
    $this['Smarty'] = $this->share(function($c) {
      $s = new Smarty();
      $s->setCompileDir("{$c['datapath']}/_smartyCompiledTemplates");
      return $s;
    });
    
    $this['Twig'] = $this->share(function($c) {
      $loader = new Twig_Loader_Filesystem($c['rootpathweb'].'/sadmin');
      $twig = new Twig_Environment($loader, array(
        'cache' => $c['datapath'].'/_twig',
      ));
      return $twig;
    });
  }

  protected function setupCore()
  {
    $this['devmode'] = true;
    $this['rootpath'] = '/Applications/MAMP/web10';
    $this['rootpathlib'] = $this['rootpath'] . '/lib';
    $this['rootpathweb'] = $this['rootpath'] . '/wwwroot';
    $this['datapath'] = '/Applications/MAMP/web10/data';
    $this['sessionCookieName'] = 'web10_session_id';

    $this['Web10\Common\Contexts\AccountContext'] = $this->share(function($c) {
      return new \Web10\Common\Contexts\AccountContext($c['Web10\Business\ContextHelper']);
    });

    $this['Web10\Common\Contexts\BlockContext'] = $this->share(function($c) {
      return new \Web10\Common\Contexts\BlockContext(
      $c['Web10\Business\ContextHelper'],
      $c['Web10\Common\Contexts\PageContext']
      );
    });

    $this['Web10\Common\Contexts\PageContext'] = $this->share(function($c) {
      return new \Web10\Common\Contexts\PageContext(
      $c['Web10\Business\ContextHelper'],
      $c['Web10\Common\Contexts\AccountContext']
      );
    });

    $this['Web10\Common\Contexts\VisitorContext'] = $this->share(function($c) {
      return new \Web10\Common\Contexts\VisitorContext($c['Web10\Business\ContextHelper']);
    });

    $this['Web10\Business\ContextHelper'] = function($c) {
      $helper = new \Web10\Business\ContextHelper();
      $helper->setHostRepo($c['Web10\Repository\HostRepo']);
      $helper->setUrlRepo($c['Web10\Repository\UrlRepo']);
      $helper->setVisitorRepo($c['Web10\Repository\VisitorRepo']);
      if (isset($c['blockType']))
      {
        $helper->setBlockRepo($c["Web10\\Repository\\Block\\$c[blockType]Repo"]);
      }
      return $helper;
    };

    $this['Web10\Common\EditingAssets'] = $this->share(function($c) {
      return new \Web10\Common\EditingAssets(
        $c['rootpathweb'], $c['Web10\Common\Contexts\AccountContext'], 
        $c['Web10\Business\FileManager'], $c['Web10\Business\PageManager'], 
        $c['Web10\Business\WebsiteDefManager']
      );
    });
    
    $this['Web10\Common\ViewingAssets'] = $this->share(function($c) {
      return new \Web10\Common\ViewingAssets($c['rootpathweb']);
    });

    $this['dbParams'] = array(
    	'driver'    => 'pdo_mysql',
    	'path'      => '127.0.0.1',
    	'dbname'    => 'web10',
    	'user'      => 'web10',
    	'password'  => '2mj9tqqshq'
    );

	$this['Web10\Business\UploadManager'] = function($c) {
	  $m = new \Web10\Business\UploadManager(
	  $c['Web10\Common\Contexts\AccountContext'],
	  $c['Web10\Repository\FileRepo'],
	  $c['Web10\Repository\ImageRepo'],
	  $c['Web10\Repository\FolderRepo'],
	  $c['rootpathweb']
	  );
	  $m->setThumbnailer($c['Web10\Business\Thumbnailer']);
	  return $m;
	};

	$this['Doctrine\ORM\EntityManager'] = $this->share(function($c) {
	  $config = \Doctrine\ORM\Tools\Setup::createAnnotationMetadataConfiguration(
	  array($c['rootpathlib'] . '/Web10/Domain'), $c['devmode']);
	  $em = \Doctrine\ORM\EntityManager::create($c['dbParams'], $config);
	  return $em;
	});

	$this['Web10\Business\CookieHelper'] = function($c) {
	  $helper = new \Web10\Business\CookieHelper($c['sessionCookieName'], $c['Web10\Repository\VisitorRepo']);
	  return $helper;
	};

	$this['Web10\Business\PageManager'] = function($c) {
	  $m = new \Web10\Business\PageManager(
	  $c['Doctrine\ORM\EntityManager'],
	  $c['Web10\Common\Contexts\AccountContext']
	  );
	  $m->setPageRepo($c['Web10\Repository\PageRepo']);
	  $m->setLayoutRepo($c['Web10\Repository\LayoutRepo']);
	  $m->setUrlRepo($c['Web10\Repository\UrlRepo']);
	  $m->setBlockRepo($c['Web10\Repository\Block\BlockRepo']);
	  return $m;
	};

	$this['Web10\Business\FileManager'] = function($c) {
	  $m = new \Web10\Business\FileManager(
	  $c['Doctrine\ORM\EntityManager'],
	  $c['Web10\Common\Contexts\AccountContext']
	  );
	  $m->setFolderRepo($c['Web10\Repository\FolderRepo']);
	  $m->setImageRepo($c['Web10\Repository\ImageRepo']);
	  $m->setFileRepo($c['Web10\Repository\FileRepo']);
	  return $m;
	};
  }
}
?>
