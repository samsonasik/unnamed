<?php

/**
 * @copyright  2015 (c) Stanimir Dimitrov.
 * @license    http://www.opensource.org/licenses/mit-license.php  MIT License
 *
 * @version    0.0.22
 *
 * @link       https://github.com/Stanimirdim92/unnamed
 */
namespace SD\Admin\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Administrator.
 *
 * @ORM\Entity
 * @ORM\Table(name="administrator")
 */
final class Administrator
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
     * @ORM\Column(name="user", type="integer", nullable=false)
     */
    private $user = 0;

    /**
     * @var array
     */
    public function exchangeArray(array $data = [])
    {
        $this->id = (isset($data['id'])) ? $data['id'] : $this->getId();
        $this->user = (isset($data['user'])) ? $data['user'] : $this->getUser();
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
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int
     */
    public function setId($id = 0)
    {
        $this->id = $id;
    }

    /**
     * Set user id.
     *
     * @param int $user
     */
    public function setUser($user = 0)
    {
        $this->user = $user;
    }

    /**
     * Get user id.
     *
     * @return int
     */
    public function getUser()
    {
        return $this->user;
    }
}
