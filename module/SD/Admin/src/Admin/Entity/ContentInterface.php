<?php

/**
 * @copyright  2015 (c) Stanimir Dimitrov.
 * @license    http://www.opensource.org/licenses/mit-license.php  MIT License
 *
 * @version    0.0.23
 *
 * @link       https://github.com/Stanimirdim92/unnamed
 */
namespace SD\Admin\Entity;

interface ContentInterface
{
    /**
     * @param array $data
     *
     * @return void
     */
    public function exchangeArray(array $data = []);

    /**
     * Used into form binding.
     *
     * @return array
     */
    public function getArrayCopy();

    /**
     * Get id.
     *
     * @return int
     */
    public function getId();

    /**
     * Set id.
     *
     * @param int
     */
    public function setId($id = 0);

    /**
     * Set Menu.
     *
     * @param int $menu
     */
    public function setMenu($menu = 0);

    /**
     * Get menu.
     *
     * @return int
     */
    public function getMenu();

    /**
     * Set title.
     *
     * @param null $title
     */
    public function setTitle($title);

    /**
     * Get title.
     *
     * @return string
     */
    public function getTitle();

    /**
     * Set titleLink.
     *
     * @param null $titleLink
     */
    public function setTitleLink($titleLink);

    /**
     * Get titleLink.
     *
     * @return string
     */
    public function getTitleLink();

    /**
     * Set author id.
     *
     * @param int $author
     */
    public function setAuthor($author = 0);

    /**
     * Get author id.
     *
     * @return int
     */
    public function getAuthor();

    /**
     * Set active.
     *
     * @param bool|int $active
     */
    public function setActive($active = true);

    /**
     * Get active.
     *
     * @return bool|int
     */
    public function isActive();

    /**
     * Set preview.
     *
     * @param string $preview
     */
    public function setPreview($preview);

    /**
     * Get preview.
     *
     * @return string
     */
    public function getPreview();

    /**
     * Set text.
     *
     * @param string $text
     */
    public function setText($text);

    /**
     * Get text.
     *
     * @return string
     */
    public function getText();

    /**
     * Set order.
     *
     * @param int $menuOrder
     */
    public function setMenuOrder($menuOrder = 0);

    /**
     * Get menuOrder.
     *
     * @return int
     */
    public function getMenuOrder();

    /**
     * Set type.
     *
     * @param int $type
     */
    public function setType($type = 0);

    /**
     * Get type.
     *
     * @return int
     */
    public function getType();

    /**
     * Set date.
     *
     * @param string $date
     */
    public function setDate($date = '0000-00-00 00:00:00');

    /**
     * Get date.
     *
     * @return string
     */
    public function getDate();

    /**
     * Set Language.
     *
     * @param int $language
     */
    public function setLanguage($language = 1);

    /**
     * Get language.
     *
     * @return int
     */
    public function getLanguage();
}
