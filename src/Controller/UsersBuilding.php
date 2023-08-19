<?php

namespace App\Controller;

use App\Entity\Building;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;


final class UsersBuilding extends AbstractController
{

    public function __invoke(Building $building, Request $request)
    {
        $building = $request->attributes->get('data');
        dd($building);

        if (!$building instanceof Building) {
            throw new \RuntimeException('Building attendu');
        }
        $apartments = $building->getApartments();

        $tenants = $apartments->getTenants();
        return $tenants;
    }
}





