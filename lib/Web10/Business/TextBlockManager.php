<?
namespace Web10\Business;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NoResultException;
use Web10\Repository\Block\TextRepo;
use Web10\Common\Contexts\AccountContext;
use Web10\Domain\Blocks\Text;

class TextBlockManager
{
  protected $repo;
  protected $website;

  public function __construct(TextRepo $repo, AccountContext $ac)
  {
    $this->repo = $repo;
    $this->website = $ac->getWebsite();
  }

  public function deleteById($id)
  {
    $t = $this->repo->getBlockById($this->website, $id);
    $this->repo->delete($t);
  }

  public function saveText(Text $t)
  {
    $this->repo->saveAndFlush($t);
  }

  public function getText($id)
  {
    return $this->repo->getBlockById($this->website, $id);
  }
}
?>
