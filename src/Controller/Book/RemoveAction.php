<?php

namespace App\Controller\Book;

use App\Entity\Book;
use App\Security\Voter\BookVoter;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RemoveAction extends AbstractController {

    #[Route('/book/{uuid}/remove', name: 'remove_book')]
    public function __invoke(#[MapEntity(mapping: ['uuid' => 'uuid'])] Book $book): Response {
        $this->denyAccessUnlessGranted(BookVoter::REMOVE, $book);
    }
}