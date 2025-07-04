<?php

namespace App\Controller\BookCopy;

use App\Checkout\CheckoutBookCopyRequest;
use App\Checkout\CheckoutManager;
use App\Entity\BookCopy;
use App\Form\CheckoutBookCopyType;
use App\Security\Voter\BookCopyVoter;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CheckoutAction extends AbstractController {

    public function __construct(private readonly CheckoutManager $checkoutManager) {

    }

    #[Route('/book/copy/{uuid}/checkout', name: 'checkout_copy')]
    public function __invoke(#[MapEntity(mapping: ['uuid' => 'uuid'])] BookCopy $bookCopy, Request $request): RedirectResponse|Response {
        $this->denyAccessUnlessGranted(BookCopyVoter::CHECKOUT, $bookCopy);

        $checkoutRequest = new CheckoutBookCopyRequest();
        $form = $this->createForm(CheckoutBookCopyType::class, $checkoutRequest);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $this->checkoutManager->checkout($bookCopy, $checkoutRequest->borrower);
            $this->addFlash('success', 'checkouts.checkout.success');

            return $this->redirectToRoute('book_copy', [
                'uuid' => $bookCopy->getUuid()
            ]);
        }

        return $this->render('checkouts/checkout.html.twig', [
            'bookCopy' => $bookCopy,
            'form' => $form->createView()
        ]);
    }
}