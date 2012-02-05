<?php
namespace Web10\Web;

use Web10\Business\WebsiteDefManager;
use Web10\Business\ContextHelper;
use Web10\Domain\Blocks\Block;
use Web10\Common\CoreContainer;
use Web10\Common\Contexts\PageContext;
use Web10\Common\Contexts\VisitorContext;
use Web10\Common\Contexts\BlockContext;
use Web10\Common\EditingAssets;
use Web10\Common\ViewingAssets;
use AssetManager\CssAsset;
use AssetManager\JsAsset;
use AssetManager\JsDataAsset;
use AssetManager\AssetManager;
use \InvalidArgumentException;
use QueryPath;
use QueryPathParseException;

class WebsiteDefHelper
{
	protected $pc;
	protected $bc;
	protected $am;
	protected $rootpathweb;

	public function __construct(PageContext $pc, BlockContext $bc, VisitorContext $vc, EditingAssets $editingAssets, ViewingAssets $viewingAssets, WebsiteDefManager $defManager, $rootpathweb, ContextHelper $contextHelper)
	{
		$this->editingAssets = $editingAssets;
		$this->viewingAssets = $viewingAssets;
		$this->bc = $bc;
		$this->rootpathweb = $rootpathweb;
		$this->page = $pc->getPage();
		$this->visitor = $vc->getVisitor();
		$this->defManager = $defManager;
		$this->contextHelper = $contextHelper;
	}

	public function render()
	{
		//$layout = $this->page->getLayout();
		//$def = $this->page->getWebsite()->getWebsiteDef();
		//$layoutPath = $this->rootpathweb . "/defs/$def/$layout.xhtml";

		$layoutPath = $this->defManager->getLayoutPath($this->page);

		$qp = new QueryPath($layoutPath);
		$this->renderPage($qp);

		return $qp->top()->html();
	}

	protected function renderPage($qp)
	{
		$this->renderTitle($qp);
		$this->renderBlocks($qp);
		$this->renderContainers($qp);

		if ($this->visitor->getIsAuthenticated())
		{
          $this->renderEditingAssets($qp);
		}
		else 
		{
		  $this->renderViewingAssets($qp);
		}
	}
	
	protected function renderViewingAssets($qp)
	{
	  $this->renderJs($qp, $this->viewingAssets->getAllByType('AssetManager\JsAsset'));
	  $this->renderCss($qp, $this->viewingAssets->getAllByType('AssetManager\CssAsset'));
	}
	
	protected function renderEditingAssets($qp)
	{
	  $assets = new AssetManager($this->rootpathweb);
	  $assets->merge($this->editingAssets);
	  $assets->merge($this->viewingAssets);
	  $this->renderJs($qp, $assets->getAllByType('AssetManager\JsAsset'));
	  $this->renderCss($qp, $assets->getAllByType('AssetManager\CssAsset'));
	  $this->renderToolbar($qp);
	  $this->renderJsDataScript($qp, $assets->getAllByType('AssetManager\JsDataAsset'));
	}

	protected function renderJs($qp, $assets)
	{
    	foreach ($assets as $a)
    	{
    	  $script = "<script type='text/javascript' language='javascript' src='{$a->getUrl()}'></script>\n";
    	  $qp->top()->find('head')->append($script);
    	}
	}

	protected function renderCss($qp, $assets)
	{
		foreach ($assets as $a)
		{
			$link = "<link type='text/css' rel='stylesheet' href='{$a->getUrl()}' />\n";
			$qp->top()->find('head')->append($link);
		}
	}

	protected function renderBlocks($qp)
	{
		foreach ($qp->top()->find('block') as $block)
		{
			$this->renderBlock($qp, $block);
		}
	}
	
    protected function renderContainers($qp)
	{
		foreach ($qp->top()->find('container') as $container)
		{
			$this->renderContainer($qp, $container);
		}
	}

	protected function addBlockAssets(Block $block)
	{
		$blockType = $block->getBlockType();

		$this->editingAssets->addFileAssets("/js/Web10/Block/$blockType/*.css");
		$this->editingAssets->addFileAssets("/js/Web10/Block/$blockType/*.js");

		$blockType = 'Web10.Domain.Block.' . $blockType;
		$this->editingAssets->addAsset(new JsDataAsset(array('blockModel', $block->getId()), $block, $blockType));
	}

	protected function renderContainer($qp, $tag)
	{
		$blockType = $tag->attr('type');
		$blockName = $tag->attr('name');
		if ($tag->attr('scope'))
		  $blockScope = strtoupper($tag->attr('scope'));
		else
		  $blockScope = 'PAGE';

		$c = CoreContainer::getStatic();
		$c['blockType'] = 'Container'; //need this to build the correct blockrepo subclass
		$h = $c->get('Web10\Business\ContextHelper');
		  
		$params = $this->getExtraParams($tag);
		
		$container = $h->getBlockByName($this->page, $blockType, $blockName, $blockScope, null, $params);
		$this->addBlockAssets($container);
		
		$bc = $c->get('Web10\Common\Contexts\BlockContext');
		$bc->setupByBlock($container);
		$controller = $c->get('Web10\Web\Blocks\Container\Controller');
		
		$html = $controller->view();
		
	    try
		{
		  $tag->replaceWith($html);
		}
		catch (QueryPathParseException $ex)
		{
    	  $partial = new QueryPath($html);
    	  $partial->find('.block')->html('BAD HTML');
    	  $tag->replaceWith($partial->top()->find('.block-wrapper')->html());
		}
	}
	
	protected function getExtraParams($blockTag)
	{
		$params = array();
		foreach ($blockTag->attr() as $name=>$value)
		{
			if ($name == 'type') continue;
			if ($name == 'name') continue;
			if ($name == 'scope') continue;
			$params[$name] = $value;
		}
		return $params;
	}
	
	protected function renderBlock($qp, $blockTag)
	{
		$blockType = $blockTag->attr('type');
		$blockName = $blockTag->attr('name');
		if ($blockTag->attr('scope'))
		  $blockScope = strtoupper($blockTag->attr('scope'));
		else
		  $blockScope = 'PAGE';

        $params = $this->getExtraParams($blockTag);

		//At this point, we are in the context of a block, so configure the blockcontext
		$c = CoreContainer::getStatic();
		$c['blockType'] = $blockType; //need this to build the correct blockrepo subclass
		$h = $c->get('Web10\Business\ContextHelper');
		$b = $h->getBlockByName($this->page, $blockType, $blockName, $blockScope, null, $params);
		$bc = $c->get('Web10\Common\Contexts\BlockContext');
		$bc->setupByBlock($b);

		$this->addBlockAssets($b);

		//$viewCls = "Web10\\Web\\Blocks\\$blockType\\View";
		//$view = $c->get($viewCls);
		//$html = $view->render();
		$ctrlCls = "Web10\\Web\\Blocks\\$blockType\\Controller";
		$ctrl = $c->get($ctrlCls);
		$html = $ctrl->view();
		
		try
		{
		  $blockTag->replaceWith($html);
		}
		catch (QueryPathParseException $ex)
		{
    	  $partial = new QueryPath($html);
    	  $partial->find('.block')->html('BAD HTML');
    	  $blockTag->replaceWith($partial->top()->find('.block-wrapper')->html());
		}
	}

	protected function renderTitle($qp)
	{
		$title = $this->page->getTitle();
		$qp->top()->find('head')->append("<title>$title</title>\n");
	}

	public function renderJsDataScript($qp, $assets)
	{
		$js = $this->getJsDataScript($assets);
		$qp->top()->find('head')->append("<script type='text/javascript' language='javascript'>\n<!--\n$js\n-->\n</script>\n");
	}

	public function getJsDataScript($assets)
	{
		$dicts = array();
		$js = "\nWeb10.Data = {};\n";
		foreach ($assets as $a)
		{
			$var = $a->getVar();
			$json = $a->getJsonEntity();
			$cls = $a->getCls();

			//this type of variable is just regular
			if (gettype($var) == 'string')
			{
				if ($cls)
				$js .= "Web10.Data.$var = new $cls($json);\n";
				else
				$js .= "Web10.Data.$var = $json;\n";
			}
			//this case indicates a values in an dictionary hashed by a key
			else if (gettype($var) == 'array')
			{
				list($dict, $key) = $var;
				//store the dictionary name so that we only initialize it once
				if (! in_array($dict, $dicts))
				{
					$js .= "Web10.Data.$dict = {};\n";
					$dicts[] = $dict;
				}
				//now we can set the data
				if ($cls)
				$js .= 'Web10.Data.' . $dict . '[' . $key . "] = new $cls($json);\n";
				else
				$js .= 'Web10.Data.' . $dict . '[' . $key . "] = $json;\n";
		    }  
		}
		return $js;
	}

	protected function renderToolbar($qp)
	{
		$qp->top()->find('body')->prepend($this->getToolbar());
	}

        protected function getToolbar()
        {
                $html  = "<table width='100%' id='toolbar'><tr>";
                $html .= "<td id='menubar' align='left'>";
                $html .= "<a href='/login.php?action=logout'><span class='icon'>X</span>Logout</a>";
                $html .= "<a href='javascript:void(0);' id='fileManagerButton'><span class='icon'>Z</span>Files</a>";
                $html .= "<a href='javascript:void(0);' id='pageManagerButton'><span class='icon'>a</span>Pages</a>";
                $html .= "</td>";
                $html .= "<td></td>";
                $html .= "<td align='right' id='rightTools'>";
                $html .= "<span id='editText'>Editing:</span>";
                $html .= "<div id='editingMode'><a href='javascript:void(0);' id='on' name='on'><span>On</span></a> <a href='javascript:void(0);' id='off' name='off'><span>Off</span></a></div>";
                $html .= "<img id='logo' src='/img/logo.png' alt='Websutes.ca' />";
                $html .= "</td></tr></table>\n\n";
                return $html;
        }
}
?>
