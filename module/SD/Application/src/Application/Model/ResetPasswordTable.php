<?php

/**
 * @copyright  2015 - 2016 (c) Stanimir Dimitrov.
 * @license    http://www.opensource.org/licenses/mit-license.php  MIT License
 *
 * @version    0.0.25
 *
 * @link       https://github.com/Stanimirdim92/unnamed
 */
namespace SD\Application\Model;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use SD\Application\Entity\ResetPassword;
use SD\Application\Exception\RuntimeException;

final class ResetPasswordTable implements ResetPasswordTableInterface
{
    /*
     * @var ObjectManager
     */
    private $entityManager;

    /*
     * @var object
     */
    private $objectRepository;

    /**
     * @param ObjectManager    $entityManager
     * @param ObjectRepository $objectRepository
     */
    public function __construct(ObjectManager $entityManager, ObjectRepository $objectRepository)
    {
        $this->entityManager = $entityManager;
        $this->objectRepository = $objectRepository;
    }

    /**
     * @return object
     */
    public function queryBuilder()
    {
        return $this->entityManager->createQueryBuilder();
    }

    /**
     * @return object
     */
    public function getEntityRepository()
    {
        return $this->objectRepository;
    }

    /**
     * This method returns a single row which verifies that this is the user that needs to reset his password.
     *
     * @param int $id   user id
     * @param int $user
     *
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws RuntimeException
     *
     * @return ResetPassword
     */
    public function getResetPassword($id = 0, $user = 0)
    {
        $resetPassword = $this->queryBuilder();
        $resetPassword->select(['r']);
        $resetPassword->from('SD\Application\Entity\ResetPassword', 'r');
        $resetPassword->where('r.id = :id AND r.user = :user');
        $resetPassword->setParameter(':id', (int) $id);
        $resetPassword->setParameter(':user', (int) $user);
        $resetPassword = $resetPassword->getQuery()->getSingleResult();

        if (empty($resetPassword)) {
            throw new RuntimeException("Couldn't find record");
        }

        return $resetPassword;
    }

    /**
     * Save or update password based on the provided id.
     *
     * @param ResetPassword $resetPassword
     *
     * @return ResetPassword
     */
    public function saveResetPassword(ResetPassword $resetPassword)
    {
        $this->entityManager->persist($resetPassword);
        $this->entityManager->flush();

        return $resetPassword;
    }
}
