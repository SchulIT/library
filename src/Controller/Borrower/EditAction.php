<?php

namespace App\Controller\Borrower;

use App\Entity\Borrower;
use App\Form\BorrowerType as BorrowerFormType;
use App\Repository\BorrowerRepositoryInterface;
use App\Security\Voter\BorrowerVoter;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class EditAction extends AbstractController {
    public function __construct(private readonly BorrowerRepositoryInterface $repository) {

    }

    #[Route('/borrower/{uuid}/edit', name: 'edit_borrower')]
    public function __invoke(#[MapEntity(mapping: ['uuid' => 'uuid'])] Borrower $borrower, Request $request): Response {
        $this->denyAccessUnlessGranted(BorrowerVoter::EDIT, $borrower);

        $form = $this->createForm(BorrowerFormType::class, $borrower);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->repository->persist($borrower);
            $this->addFlash('success', 'borrowers.edit.success');
            return $this->redirectToRoute('borrowers');
        }

        return $this->render('borrowers/edit.html.twig', [
            'borrower' => $borrower,
            'form' => $form->createView()
        ]);
    }
}