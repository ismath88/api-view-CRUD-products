<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;

class JwtUserLastLogonUpdaterListener
{
    /**
     * @var AccessDecisionManagerInterface
     */
    private $decisionManager;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @param AccessDecisionManagerInterface $decisionManager
     * @param EntityManagerInterface         $entityManager
     * @param TokenStorageInterface          $tokenStorage
     */
    public function __construct(AccessDecisionManagerInterface $decisionManager, EntityManagerInterface $entityManager, TokenStorageInterface $tokenStorage)
    {
        $this->decisionManager = $decisionManager;
        $this->entityManager = $entityManager;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @param JWTCreatedEvent $event
     */
    public function onJWTCreated(JWTCreatedEvent $event)
    {
        $token = $this->tokenStorage->getToken();
        if (null === $token) {
            return;
        }

        $authenticatedUser = $token->getUser();
        if (!$authenticatedUser instanceof User) {
            return;
        }

        $authenticatedUser->setDateLastLogon(new \DateTime());

        $this->entityManager->persist($authenticatedUser);
        $this->entityManager->flush();
    }
}
