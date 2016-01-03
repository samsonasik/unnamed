<?php

/**
 * @copyright  2015 (c) Stanimir Dimitrov.
 * @license    http://www.opensource.org/licenses/mit-license.php  MIT License
 *
 * @version    0.0.25
 *
 * @link       https://github.com/Stanimirdim92/unnamed
 */
namespace SD\Admin\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Cats.
 *
 * @ORM\Entity
 * @ORM\Table(name="contents_categories")
 */
final class Cats
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="id", type="integer", nullable=false)
     */
    private $id = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="content_id", type="integer", nullable=false)
     */
    private $content_id;

    /**
     * @var int
     *
     * @ORM\Column(name="category_id", type="integer", nullable=false)
     */
    private $category_id;

    /**
     * @param array $data
     */
    public function exchangeArray(array $data = [])
    {
        if (!empty($data)) {
            // We need to extract all default values defined for this entity
            // and make a comparsion between both arrays
            $arrayCopy = $this->getArrayCopy();

            foreach ($data as $key => $value) {
                if (in_array($key, $arrayCopy)) {
                    $this->{$key} = $value;
                }
            }
        }
    }

    /**
     * Used into form binding.
     */
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    /**
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $this->exchangeArray($options);
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set id.
     *
     * @param int
     */
    public function setId($id = 0)
    {
        $this->id = $id;
    }

    /**
     * Set content_id.
     *
     * @param int $content_id
     */
    public function setContentId($content_id)
    {
        $this->content_id = $content_id;
    }

    /**
     * Get content_id.
     *
     * @return int
     */
    public function getContentId()
    {
        return $this->content_id;
    }

    /**
     * Set category_id.
     *
     * @param int $category_id
     */
    public function setCategoryId($category_id)
    {
        $this->category_id = $category_id;
    }

    /**
     * Get category_id.
     *
     * @return int
     */
    public function getCategoryId()
    {
        return $this->category_id;
    }
}
