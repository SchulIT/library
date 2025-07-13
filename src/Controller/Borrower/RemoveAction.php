<?php

namespace App\Controller\Borrower;

use App\Checkout\CheckoutManager;
use App\Entity\Borrower;
use App\Repository\BorrowerRepositoryInterface;
use App\Repository\CheckoutRepositoryInterface;
use App\Security\Voter\BorrowerVoter;
use SchulIT\CommonBundle\Form\ConfirmType;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class RemoveAction extends AbstractController {

    public function __construct(private readonly BorrowerRepositoryInterface $repository,
                                private readonly CheckoutRepositoryInterface $checkoutRepository,
                                private readonly CheckoutManager $checkoutManager,
                                private readonly TranslatorInterface $translator) {

    }

    #[Route('/borrower/{uuid}/remove', name: 'remove_borrower')]
    public function remove(#[MapEntity(mapping: ['uuid' => 'uuid'])] Borrower $borrower, Request $request): Response {
        $this->denyAccessUnlessGranted(BorrowerVoter::REMOVE, $borrower);

        $form = $this->createForm(ConfirmType::class, [], [
            'message' => 'borrowers.remove.confirm',
            'message_parameters' => [
                '%firstname%' => $borrower->getFirstname(),
                '%lastname%' => $borrower->getLastname(),
                '%type%' => $borrower->getType()->trans($this->translator),
            ]
        ]);
        $form->handleRequest($request);

        $activeCheckouts = $this->checkoutRepository->findActiveByBorrower($borrower);

        if($form->isSubmitted() && $form->isValid()) {
            $this->checkoutManager->endAllActiveCheckoutsForBorrower($borrower);
            $this->repository->remove($borrower);

            $this->addFlash('success', 'borrowers.remove.success');
            return $this->redirectToRoute('borrowers');
        }

        return $this->render('borrowers/remove.html.twig', [
            'borrower' => $borrower,
            'form' => $form->createView(),
            'activeCheckouts' => $activeCheckouts,
        ]);
    }
}