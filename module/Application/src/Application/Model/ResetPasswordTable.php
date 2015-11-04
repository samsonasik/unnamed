<?php

/**
 * @copyright  2015 (c) Stanimir Dimitrov.
 * @license    http://www.opensource.org/licenses/mit-license.php  MIT License
 *
 * @version    0.0.21
 *
 * @link       TBA
 */
namespace Application\Model;

use Application\Entity\ResetPassword;
use Application\Exception\RuntimeException;
use Doctrine\ORM\EntityManager;

final class ResetPasswordTable
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function queryBuilder()
    {
        return $this->entityManager->createQueryBuilder();
    }

    /**
     * @return \Doctrine\ORM\EntityRepository
     */
    public function getEntityRepository()
    {
        return $this->entityManager->getRepository('Application\\Entity\\ResetPassword');
    }

    /**
     * This method returns a single row which verifies that this is the user that needs to reset his password.
     *
     * @param int $id   user id
     * @param int $user
     *
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     *
     * @return ResetPassword If row is not found
     */
    public function getResetPassword($id = 0, $user = 0)
    {
        $resetPassword = $this->queryBuilder();
        $resetPassword->select(['r']);
        $resetPassword->from('Application\Entity\ResetPassword', 'r');
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
