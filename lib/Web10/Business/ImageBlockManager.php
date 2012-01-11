<?
namespace Web10\Business;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NoResultException;
use Web10\Repository\Block\ImageRepo;
use Web10\Common\Contexts\AccountContext;
use Web10\Domain\Blocks\Image;

class ImageBlockManager
{
  protected $repo;
  protected $website;

  public function __construct(ImageRepo $repo, AccountContext $ac)
  {
    $this->repo = $repo;
    $this->website = $ac->getWebsite();
  }

  public function saveImageBlock(Image $b)
  {
    $this->repo->saveAndFlush($b);
  }

  public function getImageBlock($id)
  {
    return $this->repo->getBlockById($this->website, $id);
  }
}
?>
