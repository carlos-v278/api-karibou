<?php

namespace App\Controller;

use App\Entity\Advertisement;
use App\Entity\AdvertisementPicture;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
final class GenerateRentReceiptController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager, private PdfGenerator $pdfGenerator)
    {

    }
    public function __invoke(Advertisement $advertisement, Request $request): AdvertisementPicture
    {
        $advertisement = $request->attributes->get('data');

        if (!$advertisement instanceof Advertisement) {
            throw new \RuntimeException(' Advertisement entendu');
        }
        $advertisementPicture = new AdvertisementPicture();
        $advertisementPicture->setAdvertisement($advertisement);

        $advertisementPicture->setUrlFile($request->files->get('file'));
        $advertisementPicture->setCreatedAt(new \DateTimeImmutable());
        $advertisementPicture->setUpdateAt(new \DateTimeImmutable());
        return $advertisementPicture;
    }
}





