<?php

namespace App\Controller\Book;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepositoryInterface;
use App\Security\Voter\BookVoter;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class EditAction extends AbstractController {
    public function __construct(private readonly BookRepositoryInterface $repository) { }

    #[Route('/book/{uuid}/edit', name: 'edit_book')]
    public function __invoke(#[MapEntity(mapping: ['uuid' => 'uuid'])] Book $book, Request $request): Response {
        $this->denyAccessUnlessGranted(BookVoter::EDIT, $book);

        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $this->repository->persist($book);

            $this->addFlash('success', 'books.add.success');
            return $this->redirectToRoute('books');
        }

        return $this->render('books/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }
}