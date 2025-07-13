<?php

namespace App\Controller\Borrower;

use App\Borrower\BorrowerReportGenerator;
use App\Entity\BorrowerType;
use App\Repository\BorrowerRepositoryInterface;
use App\Security\Voter\BorrowerVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Throwable;

class IndexAction extends AbstractController {

    public const string CHECK_VALUE = '✓';

    public function __construct(private readonly BorrowerRepositoryInterface $repository, private readonly BorrowerReportGenerator $borrowerReportGenerator) {

    }

    #[Route('/borrower', name: 'borrowers')]
    public function __invoke(Request $request): Response {
        $this->denyAccessUnlessGranted(BorrowerVoter::SHOW_ANY);

        $page = $request->query->getInt('page', 1);
        $limit = $request->query->getInt('limit', 50);
        $grade = $request->query->get('grade', null);
        $searchQuery = $request->query->get('q', null);
        $onlyWithActiveCheckouts = $request->query->get('active_checkouts') === self::CHECK_VALUE;

        try {
            $selectedType = [ BorrowerType::from($request->query->get('type', null)) ];
        } catch(Throwable) {
            $selectedType = BorrowerType::cases();
        }

        if(empty($grade)) {
            $grade = null;
        }

        if(empty($searchQuery)) {
            $searchQuery = null;
        }

        $result = $this->repository->find($selectedType, $grade, $page, $limit, $searchQuery, $onlyWithActiveCheckouts);

        if(!empty($searchQuery) && $result->totalCount === 1) {
            return $this->redirectToRoute('show_borrower', [
                'uuid' => $result->result[0]->getUuid()
            ]);
        }

        $grades = $this->repository->findGrades();
        $reports = [ ];

        foreach($result->result as $borrower) {
            $reports[$borrower->getId()] = $this->borrowerReportGenerator->generateReportForBorrower($borrower);
        }

        return $this->render('borrowers/index.html.twig', [
            'borrowers' => $result->result,
            'page' => $page,
            'pages' => ceil((double)$result->totalCount / $limit),
            'grade' => $grade,
            'grades' => $grades,
            'query' => $searchQuery,
            'types' => BorrowerType::cases(),
            'selectedType' => count($selectedType) > 1 ? null : $selectedType[0],
            'reports' => $reports,
            'active_checkouts' => $onlyWithActiveCheckouts
        ]);
    }
}