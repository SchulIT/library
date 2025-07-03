<?php

namespace App\Controller\BookCopy;

use App\Entity\BookCopy;
use App\Repository\BookCopyRepositoryInterface;
use App\Security\Voter\BookCopyVoter;
use SchulIT\CommonBundle\Form\ConfirmType;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RemoveAction extends AbstractController {
    public function __construct(private readonly BookCopyRepositoryInterface $repository) {

    }

    #[Route('/book/copy/{uuid}/remove', name: 'remove_book_copy')]
    public function remove(#[MapEntity(mapping: ['uuid' => 'uuid'])] BookCopy $bookCopy, Request $request): Response {
        $this->denyAccessUnlessGranted(BookCopyVoter::REMOVE, $bookCopy);

        $form = $this->createForm(ConfirmType::class, [], [
            'message' => 'books.copies.remove.confirm',
            'message_parameters' => [
                '%id%' => $bookCopy->getId(),
                '%book%' => $bookCopy->getBook()->getTitle()
            ]
        ]);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $this->repository->remove($bookCopy);
            $this->addFlash('success', 'books.copies.remove.success');

            return $this->redirectToRoute('show_book', [
                'uuid' => $bookCopy->getBook()->getUuid()
            ]);
        }

        return $this->render('books/copy/remove.html.twig', [
            'copy' => $bookCopy,
            'book' => $bookCopy->getBook(),
            'form' => $form->createView()
        ]);
    }
}