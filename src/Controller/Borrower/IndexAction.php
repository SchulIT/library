<?php

namespace App\Controller\Borrower;

use App\Entity\BorrowerType;
use App\Repository\BorrowerRepositoryInterface;
use App\Security\Voter\BorrowerVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Throwable;

class IndexAction extends AbstractController {
    public function __construct(private readonly BorrowerRepositoryInterface $repository) {

    }

    #[Route('/borrower', name: 'borrowers')]
    public function __invoke(Request $request): Response {
        $this->denyAccessUnlessGranted(BorrowerVoter::SHOW_ANY);

        $page = $request->query->getInt('page', 1);
        $limit = $request->query->getInt('limit', 50);
        $grade = $request->query->get('grade', null);
        $searchQuery = $request->query->get('q', null);

        try {
            $selectedType = [ BorrowerType::from($request->query->get('type', null)) ];
        } catch(Throwable) {
            $selectedType = BorrowerType::cases();
        }

        $result = $this->repository->find($selectedType, $grade, $page, $limit, $searchQuery);

        if(!empty($searchQuery) && $result->totalCount === 1) {
            return $this->redirectToRoute('show_borrower', [
                'uuid' => $result->result[0]->getUuid()
            ]);
        }

        $grades = $this->repository->findGrades();

        return $this->render('borrowers/index.html.twig', [
            'borrowers' => $result->result,
            'page' => $page,
            'pages' => ceil((double)$result->totalCount / $limit),
            'grade' => $grade,
            'grades' => $grades,
            'query' => $searchQuery,
            'types' => BorrowerType::cases(),
            'selectedType' => count($selectedType) > 1 ? null : $selectedType[0]
        ]);
    }
}