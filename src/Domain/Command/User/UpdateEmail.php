<?php

declare(strict_types=1);

namespace App\Domain\Command\User;

use App\Entity\User;

/**
 * Updates email.
 */
class UpdateEmail
{
    /**
     * @var string
     */
    private $username;

    /**
     * @var User
     */
    private $user;

    /**
     * @param User   $user
     * @param string $username
     */
    public function __construct(User $user, string $username)
    {
        $this->user = $user;
        $this->username = $username;
    }

    /**
     * Gets username.
     *
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * Gets the user.
     *
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }
}
