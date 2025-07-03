<?php

namespace App\DarkMode;

use App\Entity\User;
use App\Repository\UserRepositoryInterface;
use SchulIT\CommonBundle\DarkMode\DarkModeManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class DarkModeManager implements DarkModeManagerInterface {

    public function __construct(private readonly TokenStorageInterface $tokenStorage, private readonly UserRepositoryInterface $userRepository)
    {
    }

    public function enableDarkMode(): void {
    }

    public function disableDarkMode(): void {
    }

    public function isDarkModeEnabled(): bool {
        return false;
    }
}