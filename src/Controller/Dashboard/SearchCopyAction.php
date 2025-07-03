<?php

namespace App\Controller\Dashboard;

use App\Repository\BookCopyRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class SearchCopyAction extends AbstractController {
    #[Route('/dashboard/search_copy', name: 'dashboard_search_copy')]
    public function __invoke(Request $request, BookCopyRepositoryInterface $bookCopyRepository): RedirectResponse {
        $id = $request->query->get('q');
        $id = ltrim($id, '0');

        if(!is_numeric($id)) {
            $this->addFlash('error', 'dashboard.search.copy.error.invalid_value');
            return $this->redirectToRoute('dashboard');
        }

        $copy = $bookCopyRepository->findById($id);

        if($copy === null) {
            $this->addFlash('error', 'dashboard.search.copy.error.not_found');
            return $this->redirectToRoute('dashboard');
        }

        return $this->redirectToRoute('book_copy', [
            'uuid' => $copy->getUuid()
        ]);
    }
}