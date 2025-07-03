<?php

namespace App\Controller\BookCopy;

use App\Entity\BookCopy;
use App\Form\BookCopyType;
use App\Repository\BookCopyRepositoryInterface;
use App\Security\Voter\BookCopyVoter;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class EditAction extends AbstractController {

    public function __construct(private readonly BookCopyRepositoryInterface $repository) {

    }

    #[Route('/book/copy/{uuid}/edit', name: 'edit_book_copy')]
    public function edit(#[MapEntity(mapping: ['uuid' => 'uuid'])] BookCopy $bookCopy, Request $request): Response {
        $this->denyAccessUnlessGranted(BookCopyVoter::EDIT, $bookCopy);

        $form = $this->createForm(BookCopyType::class, $bookCopy);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $this->repository->persist($bookCopy);
            $this->addFlash('success', 'books.copies.edit.success');

            return $this->redirectToRoute('book_copy', [
                'uuid' => $bookCopy->getUuid()
            ]);
        }

        return $this->render('books/copy/edit.html.twig', [
            'book' => $bookCopy->getBook(),
            'copy' => $bookCopy,
            'form' => $form->createView()
        ]);
    }
}