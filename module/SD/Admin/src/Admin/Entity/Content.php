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
 * Content.
 *
 * @ORM\Entity
 * @ORM\Table(name="content")
 */
final class Content
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
     * @ORM\Column(name="menu", type="integer", nullable=false)
     */
    private $menu = 0;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=200, nullable=true)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="preview", type="string", length=100, nullable=true)
     */
    private $preview;

    /**
     * @var string
     *
     * @ORM\Column(name="text", type="text", nullable=true)
     */
    private $text;

    /**
     * @var int
     *
     * @ORM\Column(name="menuOrder", type="integer", nullable=false)
     */
    private $menuOrder = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="type", type="smallint", nullable=false)
     */
    private $type = 0;

    /**
     * @var string
     *
     * @ORM\Column(name="date", type="string", nullable=false)
     */
    private $date = '0000-00-00 00:00:00';

    /**
     * @var int
     *
     * @ORM\Column(name="language", type="integer", nullable=false)
     */
    private $language = 1;

    /**
     * @var string
     *
     * @ORM\Column(name="titleLink", type="string", length=255, nullable=true)
     */
    private $titleLink;

    /**
     * @var bool|int
     *
     * @ORM\Column(name="active", type="smallint", nullable=false)
     */
    private $active = true;

    /**
     * @var int
     *
     * @ORM\Column(name="author", type="integer", nullable=false)
     */
    private $author = 0;

    /**
     * @param array $data
     */
    public function exchangeArray(array $data = [])
    {
        // We need to extract all default values defined for this entity
        // and make a comparsion between both arrays
        $arrayCopy = $this->getArrayCopy();

        foreach ($data as $key => $value) {
            if (isset($arrayCopy[$key])) {
                $this->{$key} = $value;
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
     * constructor.
     *
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
     * Set Menu.
     *
     * @param int $menu
     */
    public function setMenu($menu = 0)
    {
        $this->menu = $menu;
    }

    /**
     * Get menu.
     *
     * @return int
     */
    public function getMenu()
    {
        return $this->menu;
    }

    /**
     * Set title.
     *
     * @param null $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Get title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set titleLink.
     *
     * @param null $titleLink
     */
    public function setTitleLink($titleLink)
    {
        $this->titleLink = $titleLink;
    }

    /**
     * Get titleLink.
     *
     * @return string
     */
    public function getTitleLink()
    {
        return $this->titleLink;
    }

    /**
     * Set author id.
     *
     * @param int $author
     */
    public function setAuthor($author = 0)
    {
        $this->author = $author;
    }

    /**
     * Get author id.
     *
     * @return int
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set active.
     *
     * @param bool|int $active
     */
    public function setActive($active = true)
    {
        $this->active = $active;
    }

    /**
     * Get active.
     *
     * @return bool|int
     */
    public function isActive()
    {
        return $this->active;
    }

    /**
     * Set preview.
     *
     * @param string $preview
     */
    public function setPreview($preview)
    {
        $this->preview = $preview;
    }

    /**
     * Get preview.
     *
     * @return string
     */
    public function getPreview()
    {
        return $this->preview;
    }

    /**
     * Set text.
     *
     * @param string $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * Get text.
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set order.
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
     * Set type.
     *
     * @param int $type
     */
    public function setType($type = 0)
    {
        $this->type = $type;
    }

    /**
     * Get type.
     *
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set date.
     *
     * @param string $date
     */
    public function setDate($date = '0000-00-00 00:00:00')
    {
        $this->date = $date;
    }

    /**
     * Get date.
     *
     * @return string
     */
    public function getDate()
    {
        return $this->date;
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
}
