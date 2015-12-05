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
 * Language.
 *
 * @ORM\Entity
 * @ORM\Table(name="language")
 * @ORM\Entity(repositoryClass="SD\Admin\Repository\LanguageRepository")
 */
final class Language
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
     * @ORM\Column(name="name", type="string", length=100, nullable=true)
     */
    private $name;

    /**
     * @var bool|int
     *
     * @ORM\Column(name="active", type="smallint", nullable=false)
     */
    private $active = true;

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
     *
     * @return $this
     */
    public function setId($id = 0)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Set name.
     *
     * @param null $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set active.
     *
     * @param bool|int $active
     *
     * @return $this
     */
    public function setActive($active = true)
    {
        $this->active = $active;

        return $this;
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
}
