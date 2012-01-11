<?
namespace Web10\Business;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NoResultException;
use Web10\Repository\VisitorRepo;
use Web10\Repository\AccountRepo;
use Web10\Common\Contexts\VisitorContext;

class Authentication
{
  protected $context;
  protected $visitorRepo;
  protected $accountRepo;

  public function __construct(VisitorContext $vc, AccountRepo $accountRepo, VisitorRepo $visitorRepo)
  {
    $this->accountRepo = $accountRepo;
    $this->visitorRepo = $visitorRepo;
    $this->visitor = $vc->getVisitor();
  }

  public function isAuthenticated()
  {
    return $this->visitor->getIsAuthenticated();
  }

  public function login($email, $password)
  {
    try
    {
      $account = $this->accountRepo->getAccount($email, $password);
      $this->visitor->setAccount($account);
      $this->visitor->setIsAuthenticated(true);
      $this->visitorRepo->saveAndFlush($this->visitor);
      return true;
    }
    catch (NoResultException $ex)
    {
      return false;
    }
  }

  public function logout()
  {
    $this->visitor->setIsAuthenticated(false);
    $this->visitorRepo->saveAndFlush($this->visitor);
  }
}
?>
