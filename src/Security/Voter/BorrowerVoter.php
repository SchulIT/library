<?php

namespace App\Security\Voter;

use App\Entity\Borrower;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Vote;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class BorrowerVoter extends Voter {

    public const string ADD = 'add-borrower';
    public const string IMPORT = 'import-borrowers';
    public const string EDIT = 'edit';
    public const string REMOVE = 'remove';

    public const string SHOW_ANY = 'show-borrowers';

    public const string SHOW = 'show';

    public function __construct(private readonly AccessDecisionManagerInterface $accessDecisionManager) { }

    protected function supports(string $attribute, mixed $subject): bool {
        return $attribute === self::ADD
            || $attribute === self::IMPORT
            || $attribute === self::SHOW_ANY
            || ($subject instanceof Borrower && in_array($attribute, [self::EDIT, self::REMOVE, self::SHOW]));
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool {
        if($attribute === self::SHOW_ANY) {
            return $this->accessDecisionManager->decide($token, ['ROLE_LENDER']);
        }

        return $this->accessDecisionManager->decide($token, ['ROLE_BORROWERS_ADMIN']);
    }
}