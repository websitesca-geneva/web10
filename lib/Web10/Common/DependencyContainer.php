<?php
namespace Web10\Common;

use \ReflectionClass;
use \InvalidArgumentException;
use \Closure;

class DependencyContainer implements \ArrayAccess
{
  private $values = array();

  /**
   * Sets a parameter or an object.
   *
   * Objects must be defined as Closures.
   *
   * Allowing any PHP callable leads to difficult to debug problems
   * as function names (strings) are callable (creating a function with
   * the same a name as an existing parameter would break your container).
   *
   * @param string $id    The unique identifier for the parameter or object
   * @param mixed  $value The value of the parameter or a closure to defined an object
   */
  function offsetSet($id, $value)
  {
    $this->values[$id] = $value;
  }

  /**
   * Gets a parameter or an object.
   *
   * @param  string $id The unique identifier for the parameter or object
   *
   * @return mixed  The value of the parameter or an object
   *
   * @throws InvalidArgumentException if the identifier is not defined
   */
  function offsetGet($id)
  {
    //if (!array_key_exists($id, $this->values))
    //{
    //  throw new InvalidArgumentException(sprintf('Identifier "%s" is not defined.', $id));
    //}

    if ( (!isset($this->values[$id])) and (class_exists($id)))
      return $this->build($id);
    else
      return $this->values[$id] instanceof \Closure ? $this->values[$id]($this) : $this->values[$id];
  }

  public function get($name)
  {
    return $this[$name];
  }

  public function build($cls)
  {
    $r = new ReflectionClass($cls);
    $constructor = $r->getConstructor();
    if ($constructor)
    {
      $parameters = $constructor->getParameters();
      $args = $this->getArgs($cls, $parameters, '__construct');
      return $r->newInstanceArgs($args);
    }
    else
    return $r->newInstance();
  }

  public function getArgs($cls, $parameters, $method)
  {
    $args = array();
    if (count($parameters) == 0) return $args;

    foreach ($parameters as $parameter)
    {
      if ($parameter->isOptional()) continue;
      $name = $parameter->getName();

      if (isset($this[$name]))
      {
        $args[] = $this[$name];
      }
      else if ($hint = $parameter->getClass())
      {
        $args[] = $this[$hint->getName()];
      }
      else
      {
        throw new InvalidArgumentException("Trying to get
          parameter $name for $cls::$method but a context for 
          $cls has not been registered and there is no typehint either.");
      }
    }
    return $args;
  }

  /**
   * Checks if a parameter or an object is set.
   *
   * @param  string $id The unique identifier for the parameter or object
   *
   * @return Boolean
   */
  function offsetExists($id)
  {
    return isset($this->values[$id]);
  }

  /**
   * Unsets a parameter or an object.
   *
   * @param  string $id The unique identifier for the parameter or object
   */
  function offsetUnset($id)
  {
    unset($this->values[$id]);
  }

  /**
   * Returns a closure that stores the result of the given closure for
   * uniqueness in the scope of this instance of Pimple.
   *
   * @param Closure $callable A closure to wrap for uniqueness
   *
   * @return Closure The wrapped closure
   */
  function share(\Closure $callable)
  {
    return function ($c) use ($callable) {
      static $object;

      if (is_null($object)) {
        $object = $callable($c);
      }

      return $object;
    };
  }

  /**
   * Protects a callable from being interpreted as a service.
   *
   * This is useful when you want to store a callable as a parameter.
   *
   * @param Closure $callable A closure to protect from being evaluated
   *
   * @return Closure The protected closure
   */
  function protect(\Closure $callable)
  {
    return function ($c) use ($callable) {
      return $callable;
    };
  }

  /**
   * Gets a parameter or the closure defining an object.
   *
   * @param  string $id The unique identifier for the parameter or object
   *
   * @return mixed  The value of the parameter or the closure defining an object
   *
   * @throws InvalidArgumentException if the identifier is not defined
   */
  function raw($id)
  {
    if (!array_key_exists($id, $this->values)) {
      throw new InvalidArgumentException(sprintf('Identifier "%s" is not defined.', $id));
    }

    return $this->values[$id];
  }
}
