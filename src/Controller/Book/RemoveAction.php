<?php

namespace App\Controller\Book;

use App\Entity\Book;
use App\Repository\BookRepositoryInterface;
use App\Security\Voter\BookVoter;
use SchulIT\CommonBundle\Form\ConfirmType;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RemoveAction extends AbstractController {

    public function __construct(private readonly BookRepositoryInterface $repository) { }

    #[Route('/book/{uuid}/remove', name: 'remove_book')]
    public function __invoke(#[MapEntity(mapping: ['uuid' => 'uuid'])] Book $book, Request $request): Response {
        $this->denyAccessUnlessGranted(BookVoter::REMOVE, $book);

        $form = $this->createForm(ConfirmType::class, [], [
            'message' => 'books.remove.confirm',
            'message_parameters' => [
                '%book%' =>  $book->getTitle()
            ]
        ]);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $this->repository->remove($book);
            $this->addFlash('success', 'books.remove.success');

            return $this->redirectToRoute('books');
        }

        return $this->render('books/remove.html.twig', [
            'book' => $book,
            'form' => $form->createView(),
        ]);
    }
}