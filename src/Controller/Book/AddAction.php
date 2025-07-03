<?php

namespace App\Controller\Book;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepositoryInterface;
use App\Security\Voter\BookVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class AddAction extends AbstractController {

    public function __construct(private readonly BookRepositoryInterface $repository) {

    }

    #[Route('/book/add', name: 'add_book')]
    public function __invoke(Request $request): Response {
        $this->denyAccessUnlessGranted(BookVoter::ADD);

        $book = new Book();
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $this->repository->persist($book);

            $this->addFlash('success', 'books.add.success');
            return $this->redirectToRoute('books');
        }

        return $this->render('books/add.html.twig', [
            'form' => $form->createView()
        ]);
    }
}