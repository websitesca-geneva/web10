<?php
namespace Web10\Common;

class CoreClassLoader
{
  protected $callbacks;
  
  function __construct()
  {
    $this->callbacks = array();
  }
  
  public function register($clspath, \Closure $f)
  {
    $this->callbacks[$clspath] = $f;
  }
  
  public function load($cls)
  {
    foreach ($this->callbacks as $name=>$f)
    {
      if (substr($cls, 0, strlen($name)) == $name)
      {
        $cls = $f($cls);
        break;
      }
    }
    $reqr = str_replace("\\", DIRECTORY_SEPARATOR, $cls) . ".php";
    
    //print "<p>IN LOAD $cls ($reqr)";
    
    require_once($reqr);

    return true;
  }
  
  public function setup()
  {
    $this->register('QueryPath', function($cls) { return "QueryPath/$cls"; });
    
    $this->register('Web10\SAdmin', function($cls) {
      $after = substr($cls,  16);
      return "sadmin/$after"; 
    });
    
    $this->register('Smarty_Internal', function($cls) {
      $cls = strtolower($cls); 
      return "Smarty/sysplugins/$cls"; 
    });
    
    $this->register('Smarty', function($cls) { return "Smarty/$cls.class"; });
    
    //adapt the old pear class convention to namespaces
    $this->register('Twig', function($s) {
    	$parts = preg_split("/[_]+/", $s);
    	$path = implode('/', $parts);
    	return $path;
    });
    
    spl_autoload_extensions('.php');
    spl_autoload_register(array($this, 'load'));
    
  }
}
?>
