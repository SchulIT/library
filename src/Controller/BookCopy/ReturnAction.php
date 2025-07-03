<?php

namespace App\Controller\BookCopy;

use App\Entity\BookCopy;
use App\Security\Voter\BookCopyVoter;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ReturnAction extends AbstractController {
    #[Route('/book/copy/{uuid}/return', name: 'return_copy')]
    public function returnCopy(#[MapEntity(mapping: ['uuid' => 'uuid'])] BookCopy $copy): RedirectResponse|Response {
        $this->denyAccessUnlessGranted(BookCopyVoter::RETURN, $copy);
    }
}