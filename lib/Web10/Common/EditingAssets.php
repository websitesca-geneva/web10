<?php
namespace Web10\Common;

use AssetManager\AssetManager;
use AssetManager\JsAsset;
use AssetManager\CssAsset;
use AssetManager\JsDataAsset;
use Web10\Common\JsonCollection;
use Web10\Business\PageManager;
use Web10\Business\FileManager;
use Web10\Common\Contexts\AccountContext;
use Web10\Business\WebsiteDefManager;

class EditingAssets extends AssetManager
{
  public function __construct($rootpathweb, AccountContext $ac, FileManager $fm, PageManager $pm, WebsiteDefManager $wdm)
  {
    parent::__construct($rootpathweb);
    $this->ac = $ac;
    $this->fm = $fm;
    $this->pm = $pm;
    $this->websiteDefManager = $wdm;
    
    $this->setupCore();
    $this->setupPages();
    $this->setupFiles();
    $this->setupLayouts();
  }

  public function setupCore()
  {
    $this->addAsset(new JsAsset('/js/core/jquery.js'));
    $this->addAsset(new JsAsset('/js/core/Namespace.js'));
    $this->addAsset(new JsAsset('/js/core/mustache.js'));
    $this->addAsset(new JsAsset('/js/core/Underscore.js'));
    $this->addAsset(new JsAsset('/js/core/Backbone.js'));
    $this->addAsset(new JsAsset('/js/core/Class.js'));
    $this->addAsset(new JsAsset('/js/core/ioc.js'));

    $this->addAsset(new JsAsset('/js/ui/jquery-ui-1.8.14.custom.js'));
    $this->addAsset(new JsAsset('/js/core/jquery.form.js'));
    $this->addAsset(new JsAsset('/js/core/jquery.ui.nestedSortable.js'));
    $this->addAsset(new JsAsset('/js/core/jquery.cookie.js'));
    $this->addAsset(new JsAsset('/js/core/jquery.dropup.js'));
    $this->addAsset(new JsAsset('/js/core/jquery.serializeObject.js'));

    //contextDialog
    $this->addAsset(new JsAsset('/js/core/jquery.contextDialog.js'));
    $this->addAsset(new CssAsset('/css/core/jquery.contextDialog.css'));
    
    $this->addFileAssets('/asset/fancybox/*.js');
    $this->addFileAssets('/asset/fancybox/*.css');

    //$this->addAsset(new JsAsset('/js/util.js'));

    $this->addFileAssets('/js/Web10/ContainerReg/*.js');

    $this->addFileAssets('/js/Web10/Event/*.js');
    $this->addFileAssets('/js/Web10/Domain/*.js');
    $this->addFileAssets('/js/Web10/Domain/Block/*.js');

    $this->addFileAssets('/js/Web10/CommonModel/*.js');
    $this->addFileAssets('/js/Web10/Common/*.js');

    $this->addAsset(new JsAsset('/js/Web10/Dialog/TabbedDialog.js'));//baseclass needed first
    $this->addFileAssets('/js/Web10/Dialog/*.css');

    $this->addFileAssets('/js/Web10/Common/Popdown/*.css');
    $this->addFileAssets('/js/Web10/Common/Popdown/*.js');

    $this->addFileAssets('/js/Web10/Common/SmallView/*.css');
    $this->addFileAssets('/js/Web10/Common/SmallView/*.js');

    $this->addFileAssets('/js/Web10/Common/Messaging/*.css');
    $this->addFileAssets('/js/Web10/Common/Messaging/*.js');

    $this->addFileAssets('/js/Web10/Common/File/*.css');
    $this->addFileAssets('/js/Web10/Common/File/*.js');

    $this->addFileAssets('/js/Web10/Common/Folder/*.css');
    $this->addFileAssets('/js/Web10/Common/Folder/*.js');

    $this->addFileAssets('/js/Web10/Common/FolderCrumbs/*.js');

    $this->addFileAssets('/js/Web10/Tabs/*.js');
    $this->addFileAssets('/js/Web10/Tabs/Page/*.js');
    $this->addFileAssets('/js/Web10/Tabs/PageList/*.js');
    $this->addFileAssets('/js/Web10/Tabs/SelectedFiles/*.js');
    $this->addFileAssets('/js/Web10/Tabs/SelectedFiles/*.css');
    $this->addFileAssets('/js/Web10/Tabs/PageList/*.css');
    $this->addFileAssets('/js/Web10/Dialog/*.js');
    $this->addFileAssets('/js/Web10/Dialog/PageManager/*.js');

    $this->addFileAssets('/js/Web10/Tabs/FileBrowse/*.css');
    $this->addFileAssets('/js/Web10/Tabs/FileBrowse/*.js');
    $this->addFileAssets('/js/Web10/Dialog/FileManager/*.js');

    $this->addFileAssets('/js/Web10/Tabs/ImageSelector/*.css');
    $this->addFileAssets('/js/Web10/Tabs/ImageSelector/*.js');

    $this->addFileAssets('/js/Web10/Tabs/Upload/*.css');
    $this->addFileAssets('/js/Web10/Tabs/Upload/*.js');

    $this->addAsset(new JsAsset('/js/Web10/MainContainer.js'));
    $this->addAsset(new JsAsset('/js/Web10/Editing.js'));

    $this->addFileAssets('/css/Web10/*.css');
    $this->addFileAssets('/css/Web10/Web/*.css');
    $this->addAsset(new CssAsset('/css/Web10/Web/Blocks/Block.css'));

    $this->addAsset(new JsAsset('/js/config.js'));
    $this->addAsset(new JsAsset('/js/main.js'));
  }

  public function setupPages()
  {
    $pages = $this->pm->getAll();
    $pagesJson = new JsonCollection($pages);
    $this->addAsset(new JsDataAsset('pageCol', $pagesJson, 'Web10.Domain.PageList'));
  }

  public function setupLayouts()
  {
    $layouts = $this->websiteDefManager->getLayouts($this->ac->getWebsite());
    $layoutsJson = new JsonCollection($layouts);
    $this->addAsset(new JsDataAsset('layouts', $layoutsJson));
  }

  public function setupFiles()
  {
    $folders = $this->fm->getAllFolders();
    $foldersJson = new JsonCollection($folders);
    $this->addAsset(new JsDataAsset('folderCol', $foldersJson, 'Web10.Domain.FolderList'));
     
    $files = $this->fm->getAllFiles();
    $filesJson = new JsonCollection($files);
    $this->addAsset(new JsDataAsset('fileCol', $filesJson, 'Web10.Domain.FileList'));
  }
}
?>
