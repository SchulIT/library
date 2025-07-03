<?php

namespace App\Label;

use App\Entity\BookCopy;
use App\Settings\LabelSettings;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use TCPDF;

readonly class PdfCreator {

    public function __construct(private LabelSettings $labelSettings) {

    }

    public function createPdfResponse(DownloadLabelsRequest $request): Response {
        $response = new Response($this->createPdf($request), 200, [
            'Content-Type' => 'application/pdf'
        ]);

        $response->headers->set('Content-Disposition', $response->headers->makeDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, 'barcodes.pdf'));

        return $response;
    }

    /**
     * @param DownloadLabelsRequest $request
     * @return string Resulting PDF document as (binary?) string
     */
    public function createPdf(DownloadLabelsRequest $request): string {
        $pdf = $this->createTCPDF();
        $pdf->AddPage();

        $style = $this->getBarcodeStyle();

        $column = 1;
        $row = 1;

        $cellHeight = $this->labelSettings->cellHeightMM - 2 * $this->labelSettings->cellPaddingMM;
        $cellWidth = $this->labelSettings->cellWidthMM - 2 * $this->labelSettings->cellPaddingMM;

        $copies = $request->copies;
        array_unshift(
            $copies,
            ...array_fill(0, $request->offset, null)
        );

        foreach ($copies as $copy) {
            $x = $pdf->GetX();
            $y = $pdf->GetY();
            /*$pdf->setCellMargins(
                $this->labelSettings->cellPaddingMM,
                $this->labelSettings->cellPaddingMM,
                $this->labelSettings->cellPaddingMM,
                $this->labelSettings->cellPaddingMM
            );*/

            if ($copy !== null) {
                $pdf->write1DBarcode(
                    $copy->getBarcodeId(),
                    'C39',
                    $x + $this->labelSettings->cellPaddingMM,
                    $y + $this->labelSettings->cellPaddingMM,
                    $cellWidth,
                    $cellHeight * 0.5,
                    0.4,
                    $style,
                    'M'
                );
            }

            $pdf->setXY($x + $this->labelSettings->cellPaddingMM, $y + $cellHeight * 0.5);

            if($copy !== null) {
                $text = !empty($copy->getBook()->getBarcodeTitle()) ? $copy->getBook()->getBarcodeTitle() : $copy->getBook()->getTitle();
                $pdf->Cell(
                    $cellWidth,
                    $cellHeight * 0.5,
                    $text,
                    align: 'C'
                );
            }

            $pdf->setXY($x + $this->labelSettings->cellWidthMM, $y);

            if($column === $this->labelSettings->columns) {
                $pdf->Ln($this->labelSettings->cellHeightMM);
                $column = 1;
                $row++;
            } else {
                $column++;
            }

            if($row === $this->labelSettings->rows + 1) {
                $pdf->AddPage();
                $row = 1;
            }
        }

        return $pdf->Output('barcodes.pdf', 'S');
    }

    private function getBarcodeStyle(): array {
        return [
            'position' => '',
            'align' => 'C',
            'stretch' => false,
            'fitwidth' => false,
            'cellfitalign' => '',
            'border' => 0,
            'hpadding' => 'auto',
            'vpadding' => 'auto',
            'fgcolor' => [ 0, 0, 0 ], // black
            'bgcolor' => false,
            'text' => true,
            'font' => 'helvetica',
            'fontsize' => 8,
            'stretchtext' => false
        ];
    }

    private function createTCPDF(): TCPDF {
        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->setAuthor('');
        $pdf->setTitle('Barcodes');
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        $pdf->setTopMargin($this->labelSettings->topMarginMM);
        $pdf->setLeftMargin($this->labelSettings->leftMarginMM);
        $pdf->setRightMargin($this->labelSettings->rightMarginMM);

        $pdf->setAutoPageBreak(false);

        $pdf->setFont('helvetica', '', 8);

        return $pdf;
    }
}