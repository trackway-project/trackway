<?php

namespace AppBundle\Entity\Repository;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * Class UserRepository
 *
 * @package AppBundle\Entity\Repository
 */
class UserRepository extends EntityRepository implements UserProviderInterface
{
    /**
     * @param string $token
     *
     * @return User
     */
    public function findOneByConfirmationToken($token)
    {
        return $this->findOneBy(['confirmationToken' => $token]);
    }

    /**
     * @param string $email
     *
     * @return User
     */
    public function findOneByEmail($email)
    {
        return $this->findOneBy(['email' => $email]);
    }

    /**
     * @param string $username
     *
     * @return User
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function loadUserByUsername($username)
    {
        $q = $this->createQueryBuilder('u')->where('u.username = :username OR u.email = :email')->setParameter('username', $username)->setParameter('email', $username)->getQuery();

        try {
            $user = $q->getSingleResult();
        } catch (NoResultException $e) {
            throw new UsernameNotFoundException(sprintf('Unable to find an active admin AcmeUserBundle:User object identified by "%s".', $username), 0, $e);
        }

        return $user;
    }

    /**
     * @param UserInterface $user
     *
     * @return User
     */
    public function refreshUser(UserInterface $user)
    {
        $class = get_class($user);

        if (!$this->supportsClass($class)) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $class));
        }

        /** @var User $user */
        return $this->find($user->getId());
    }

    /**
     * @param string $class
     *
     * @return bool
     */
    public function supportsClass($class)
    {
        return $this->getEntityName() === $class || is_subclass_of($class, $this->getEntityName());
    }
}
