<?php
namespace Web10\Business;

use Web10\Domain\Visitor;
use Web10\Repository\VisitorRepo;
use Doctrine\ORM\NoResultException;

class CookieHelper
{
  public function __construct($sessionCookieName, VisitorRepo $visitorRepo)
  {
    $this->sessionCookieName = $sessionCookieName;
    $this->visitorRepo = $visitorRepo;
  }

  public function setupVisitor()
  {
    if (isset($_COOKIE[$this->sessionCookieName]))
    {
      $id = $_COOKIE[$this->sessionCookieName];
      if ($this->getVisitor($id))
      {
        return $id;
      }
    }

    //if we are here, the visitor is not set in the db and we just want to
    // reset their sessionId
    $id = uniqid();
    setcookie($this->sessionCookieName, $id, 0, '/');
    $this->storeVisitor($id);
    return $id;
  }

  protected function getVisitor($sessionId)
  {
    try
    {
      $v = $this->visitorRepo->getBySessionId($sessionId);
      return $v;
    }
    catch (NoResultException $ex)
    {
      return null;
    }
  }

  public function storeVisitor($sessionId)
  {
    $visitor = new Visitor();
    $visitor->setSessionId($sessionId);
    $this->visitorRepo->saveAndFlush($visitor);
    return $visitor;
  }
}
?>
