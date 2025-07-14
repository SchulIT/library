<?php

namespace App\Security\Voter;

use App\Entity\Book;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class BookVoter extends Voter {

    public const string ADD = 'add-book';
    public const string EDIT = 'edit';
    public const string REMOVE = 'remove';
    public const string SHOW = 'show';

    public function __construct(private readonly AccessDecisionManagerInterface $accessDecisionManager) {

    }

    protected function supports(string $attribute, mixed $subject): bool {
        return $attribute === self::ADD
            || ($subject instanceof Book && in_array($attribute, [self::EDIT, self::REMOVE, self::SHOW]));
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool {
        return $this->accessDecisionManager->decide($token, ['ROLE_BOOKS_ADMIN']);
    }
}