<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserRepository extends EntityRepository implements UserLoaderInterface
{
    /**
     * {@inheritdoc}
     *
     * @see http://symfony.com/doc/current/security/entity_provider.html#using-a-custom-query-to-load-the-user
     */
    public function loadUserByUsername($username): ?UserInterface
    {
        $qb = $this->createQueryBuilder('user');

        $qb->select('user')
            ->where($qb->expr()->eq('user.username', $qb->expr()->literal(\strtolower($username))))
            ->orWhere($qb->expr()->eq('lower(user.username)', 'lower(:username)'))
            ->setParameter('username', $username);

        $user = $qb->getQuery()->getOneOrNullResult();

        return $user;
    }
}
