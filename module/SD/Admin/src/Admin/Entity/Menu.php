<?php

/**
 * @copyright  2015 - 2016 (c) Stanimir Dimitrov.
 * @license    http://www.opensource.org/licenses/mit-license.php  MIT License
 *
 * @version    0.0.25
 *
 * @link       https://github.com/Stanimirdim92/unnamed
 */
namespace SD\Admin\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Menu.
 *
 * @ORM\Entity
 * @ORM\Table(name="menu")
 * @ORM\Entity(repositoryClass="SD\Admin\Repository\MenuRepository")
 */
final class Menu
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
     * @var string
     *
     * @ORM\Column(name="caption", type="string", length=200, nullable=true)
     */
    private $caption;

    /**
     * @var int
     *
     * @ORM\Column(name="menuOrder", type="integer", nullable=false)
     */
    private $menuOrder = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="language", type="smallint", nullable=false)
     */
    private $language = 1;

    /**
     * @var int
     *
     * @ORM\Column(name="parent", type="integer", nullable=false)
     */
    private $parent = 0;

    /**
     * @var string
     *
     * @ORM\Column(name="keywords", type="string", length=100, nullable=true)
     */
    private $keywords;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=150, nullable=true)
     */
    private $description;

    /**
     * @var int
     *
     * @ORM\Column(name="menutype", type="smallint", nullable=false)
     */
    private $menutype = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="footercolumn", type="integer", nullable=false)
     */
    private $footercolumn = 0;

    /**
     * @var string
     *
     * @ORM\Column(name="menulink", type="string", length=255, nullable=true)
     */
    private $menulink;

    /**
     * @var bool
     *
     * @ORM\Column(name="active", type="smallint", nullable=false)
     */
    private $active = true;

    /**
     * @var string
     *
     * @ORM\Column(name="class", type="string", length=50, nullable=true)
     */
    private $class;

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
     * Set caption.
     *
     * @param string $caption
     */
    public function setCaption($caption)
    {
        $this->caption = $caption;
    }

    /**
     * Get caption.
     *
     * @return string
     */
    public function getCaption()
    {
        return $this->caption;
    }

    /**
     * Set menuOrder.
     *
     * @param int $menuOrder
     */
    public function setMenuOrder($menuOrder = 0)
    {
        $this->menuOrder = $menuOrder;
    }

    /**
     * Get menuOrder.
     *
     * @return int
     */
    public function getMenuOrder()
    {
        return $this->menuOrder;
    }

    /**
     * Set active.
     *
     * @param bool $active
     */
    public function setActive($active = true)
    {
        $this->active = $active;
    }

    /**
     * Get active.
     *
     * @return bool
     */
    public function isActive()
    {
        return $this->active;
    }

    /**
     * Set Language.
     *
     * @param int $language
     */
    public function setLanguage($language = 1)
    {
        $this->language = $language;
    }

    /**
     * Get language.
     *
     * @return int
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Set parent.
     *
     * @param int $parent
     */
    public function setParent($parent = 0)
    {
        $this->parent = $parent;
    }

    /**
     * Get parent.
     *
     * @return int
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set keywords.
     *
     * @param string $keywords
     */
    public function setKeywords($keywords)
    {
        $this->keywords = $keywords;
    }

    /**
     * Get keywords.
     *
     * @return string
     */
    public function getKeywords()
    {
        return $this->keywords;
    }

    /**
     * Set description.
     *
     * @param null $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Get description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set menutype.
     *
     * @param int $menutype
     */
    public function setMenuType($menutype = 0)
    {
        $this->menutype = $menutype;
    }

    /**
     * Get menutype.
     *
     * @return int
     */
    public function getMenuType()
    {
        return $this->menutype;
    }

    /**
     * Set footercolumn.
     *
     * @param int $footercolumn
     */
    public function setFooterColumn($footercolumn = 0)
    {
        $this->footercolumn = $footercolumn;
    }

    /**
     * Get footercolumn.
     *
     * @return int
     */
    public function getFooterColumn()
    {
        return $this->footercolumn;
    }

    /**
     * Set menulink.
     *
     * @param string $menulink
     */
    public function setMenuLink($menulink)
    {
        $this->menulink = $menulink;
    }

    /**
     * Get menulink.
     *
     * @return string
     */
    public function getMenuLink()
    {
        return $this->menulink;
    }

    /**
     * Set class.
     *
     * @param string $class
     */
    public function setClass($class)
    {
        $this->class = $class;
    }

    /**
     * Get class.
     *
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * Get menutype name.
     *
     * @return string
     */
    public function getMenuTypeAsName()
    {
        if ($this->getMenuType() === 0) {
            return 'Main menu';
        } elseif ($this->getMenuType() === 1) {
            return 'Left menu';
        } elseif ($this->getMenuType() === 3) {
            return 'Footer menu';
        } else {
            return 'Right menu';
        }
    }
}
