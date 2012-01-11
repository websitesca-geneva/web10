<?
namespace Web10\Domain\Blocks;

use Web10\Common\JsonEntity;

/** @Entity @Table(name="block_imagegrid_image") */
class ImageGrid_Image implements JsonEntity
{
	/** @Id @Column(type="integer") @GeneratedValue */
	protected $id;
	public function getId() { return $this->id; }
	public function setId($id) { $this->id = $id; }

	/** @ManyToOne(targetEntity="Web10\Domain\Blocks\ImageGrid", inversedBy="images", cascade={"persist"}) */
	protected $imageGrid;
	public function getImageGrid() { return $this->imageGrid; }
	public function setImageGrid($val) { $this->imageGrid = $val; }

	/** @OneToOne(targetEntity="Web10\Domain\Image") @JoinColumn(name="image_id", referencedColumnName="id") */
	protected $image;
	public function getImage() { return $this->image; }
	public function setImage($val) { $this->image = $val; }
	
    /** @Column(type="integer", nullable="true") */
    protected $ordering;
    public function getOrdering() { return $this->ordering; }
    public function setOrdering($val) { $this->ordering = $val; }

	public function __construct()
	{
		
	}

	public function getJsonData()
	{
		$data = array();
		$data['imageId'] = $this->image->getId();
		$data['ordering'] = $this->ordering;
		return $data;
	}

	public function __toString()
	{
		return json_encode($this->getJsonData());
	}
}
?>
