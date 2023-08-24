<?php

namespace App\Controller;

use ApiPlatform\Api\IriConverterInterface;
use Doctrine\Persistence\ManagerRegistry;
use Dompdf\Options;
use App\Entity\Apartment;
use App\Entity\RentReceipt;
use App\Repository\ApartmentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Knp\Snappy\Pdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Twig\Environment;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;
#[AsController]
final class GenerateRentReceiptController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private Pdf $pdfGenerator,
        private IriConverterInterface $iriConverter,
        private UploaderHelper $uploaderHelper,
        private ManagerRegistry $doctrine,
        private ApartmentRepository $apartmentRepository,
        private Environment $twig,

    )
    {

    }
    public function __invoke( Request $request): RentReceipt
    {
        $em = $this->doctrine->getManager();
        $requestData = json_decode($request->getContent(), true);
        $apartmentid = $requestData['apartment'];

        $apartment = $this->apartmentRepository->find($apartmentid);

        if (!$apartment instanceof Apartment) {
            return new JsonResponse(['message' => 'Appartement non trouvé.'], 404);
        }

        $rentReceipt = new RentReceipt();
        $rentReceipt->setMonth(date('F Y'));

        $htmlContent = $this->twig->render('pdf/rent_receipt_template.html.twig', [
            'apartment' => $apartment,
            'rentReceipt' => $rentReceipt

        ]);

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($htmlContent);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $pdfContent = $dompdf->output();

        $pdfFilename = 'rent_receipt_' . time() . '.pdf';

        $uploadDirectory = $this->getParameter('kernel.project_dir') . '/public/pdf/receipt/';
        $pdfFilePath = $uploadDirectory . $pdfFilename;
        file_put_contents($pdfFilePath, $pdfContent);
        $pdfFile = new File($pdfFilePath);

        file_put_contents($uploadDirectory . $pdfFilename, $pdfContent);

        $rentReceipt->setUrlFile($pdfFile);
        $rentReceipt->setFile($pdfFilename);
        $rentReceipt->setApartment($apartment);


        $em->persist($rentReceipt);
        $em->flush();
        return $rentReceipt;
    }
}





