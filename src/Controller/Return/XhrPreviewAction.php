<?php

namespace App\Controller\Return;

use App\Checkout\CheckoutManager;
use App\Http\HttpUtils;
use App\Repository\BookCopyRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Attribute\Route;

class XhrPreviewAction extends AbstractController {
    #[Route('/return/xhr', name: 'xhr_return')]
    public function __invoke(Request $request, HttpUtils $httpUtils, BookCopyRepositoryInterface $copyRepository, CheckoutManager $checkoutManager): Response {
        $ids = $httpUtils->parseCharacterSeparatedRequestParamAsIntArray($request, 'ids');

        if(count($ids) > 100) {
            throw new BadRequestHttpException('Anfrage darf nicht mehr als 500 IDs enthalten.');
        }

        $copies = $copyRepository->findAllByIds($ids);

        return $this->render('returns/preview.html.twig', [
            'copies' => $copies,
            'manager' => $checkoutManager
        ]);
    }
}