<?php

namespace App\Controller\Borrower;

use App\Entity\Borrower;
use App\Form\BorrowerType as BorrowerFormType;
use App\Repository\BorrowerRepositoryInterface;
use App\Security\Voter\BookVoter;
use App\Security\Voter\BorrowerVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AddAction extends AbstractController {

    public function __construct(private readonly BorrowerRepositoryInterface $repository) {

    }

    #[Route('/borrower/add', name: 'add_borrower')]
    public function __invoke(Request $request): Response {
        $this->denyAccessUnlessGranted(BorrowerVoter::ADD);

        $borrower = new Borrower();
        $form = $this->createForm(BorrowerFormType::class, $borrower);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->repository->persist($borrower);
            $this->addFlash('success', 'borrowers.add.success');
            return $this->redirectToRoute('borrowers');
        }

        return $this->render('borrowers/add.html.twig', [
            'form' => $form->createView()
        ]);
    }
}