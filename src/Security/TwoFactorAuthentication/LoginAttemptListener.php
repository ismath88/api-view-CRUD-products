<?php

declare(strict_types=1);

namespace App\Security\TwoFactorAuthentication;

use App\Entity\User;
use App\Security\Guard\Token\SecurePasswordToken;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class LoginAttemptListener
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->entityManager = $em;
    }

    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
    {
        if (!$event->getAuthenticationToken() instanceof TokenInterface) {
            return;
        }

        //Check if user can do two-factor authentication
        $token = $event->getAuthenticationToken();
        $user = $token->getUser();
        $request = $event->getRequest();

        if (!$event->getAuthenticationToken()->isAuthenticated()) {
            $isFromMobile = '/mobile/login' === $request->getPathInfo();

            if (!$user instanceof User) {
                return;
            }

            if ('Inactive' === $user->getStatus()) {
                throw new BadRequestHttpException('Inactive User.');
            }

            $canLogin = false;

            if (true === $user->getMobileUser() && true === $user->getWebUser()) {
                $canLogin = true;
            } else {
                if ($isFromMobile) {
                    $canLogin = $user->getMobileUser();
                } else {
                    $canLogin = $user->getWebUser();
                }
            }

            if (!$canLogin) {
                if ($isFromMobile) {
                    throw new BadRequestHttpException("Mobile User Can't allow to login");
                }
                throw new BadRequestHttpException("Web User Can't allow to login");
            }
        }

        if ($token instanceof SecurePasswordToken) {
            return;
        }
    }
}
