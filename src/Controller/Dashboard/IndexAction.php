<?php

namespace App\Controller\Dashboard;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class IndexAction extends AbstractController {
    #[Route('/dashboard', name: 'dashboard')]
    public function indexAction(): Response {
        return $this->render('dashboard/index.html.twig', [

        ]);
    }
}