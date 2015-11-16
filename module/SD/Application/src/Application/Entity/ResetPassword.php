<?php

/**
 * @copyright  2015 (c) Stanimir Dimitrov.
 * @license    http://www.opensource.org/licenses/mit-license.php  MIT License
 *
 * @version    0.0.23
 *
 * @link       https://github.com/Stanimirdim92/unnamed
 */
namespace SD\Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ResetPassword.
 *
 * @ORM\Entity
 * @ORM\Table(name="resetpassword")
 */
final class ResetPassword
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
     * @ORM\Column(name="token", type="string", length=100, nullable=true)
     */
    private $token;

    /**
     * @var string
     *
     * @ORM\Column(name="ip", type="string", length=30, nullable=true)
     */
    private $ip;

    /**
     * @var string
     *
     * @ORM\Column(name="date", type="string", nullable=true)
     */
    private $date = '0000-00-00 00:00:00';

    /**
     * @var int
     *
     * @ORM\Column(name="user", type="integer", nullable=false)
     */
    private $user = 0;

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
     * Set token.
     *
     * @param string $token
     */
    public function setToken($token)
    {
        $this->token = $token;
    }

    /**
     * Get token.
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set ip.
     *
     * @param string $ip
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
    }

    /**
     * Get ip.
     *
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
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
     * Set user.
     *
     * @param int $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * Get user.
     *
     * @return int
     */
    public function getUser()
    {
        return $this->user;
    }
}
