<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PdfRenderController extends AbstractController
{
    #[Route('/pdf/render', name: 'app_pdf_render')]
    public function index(): Response
    {
        return $this->render('pdf/rent_receipt_template.html.twig', [
            'controller_name' => 'PdfRenderController',
        ]);
    }
}
