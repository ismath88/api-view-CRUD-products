<?php

declare(strict_types=1);

namespace App\Domain\Command\User;

class UpdateEmailHandler
{
    public function handle(UpdateEmail $command): void
    {
        $email = $command->getUsername();
        $user = $command->getUser();

        // Enforces email in lowercase.
        $user->setUsername(\strtolower($email));
    }
}
