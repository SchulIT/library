<?php

namespace App\Controller\Borrower;

use App\Entity\Borrower;
use App\Repository\BorrowerRepositoryInterface;
use App\Security\Voter\BorrowerVoter;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RemoveAction extends AbstractController {

    public function __construct(private readonly BorrowerRepositoryInterface $repository) {

    }

    #[Route('/borrower/{uuid}/remove', name: 'remove_borrower')]
    public function remove(#[MapEntity(mapping: ['uuid' => 'uuid'])] Borrower $borrower, Request $request): Response {
        $this->denyAccessUnlessGranted(BorrowerVoter::REMOVE, $borrower);
    }
}