<?php

/**
 * @copyright  2015 (c) Stanimir Dimitrov.
 * @license    http://www.opensource.org/licenses/mit-license.php  MIT License
 *
 * @version    0.0.25
 *
 * @link       https://github.com/Stanimirdim92/unnamed
 */
namespace SD\Application\Model;

use SD\Application\Entity\ResetPassword;

interface ResetPasswordTableInterface
{
    /**
     * @return object
     */
    public function queryBuilder();

    /**
     * @return object
     */
    public function getEntityRepository();

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
     * @return void
     */
    public function getResetPassword($id = 0, $user = 0);

    /**
     * Save or update password based on the provided id.
     *
     * @param ResetPassword $resetPassword
     *
     * @return ResetPassword
     */
    public function saveResetPassword(ResetPassword $resetPassword);
}
