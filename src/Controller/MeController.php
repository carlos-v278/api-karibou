<?php


namespace App\Controller;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

#[AsController]
final class MeController extends AbstractController
{

    public function __construct(private Security $security)
    {
    }

    public function __invoke(): User
    {
        return $this->security->getUser();
    }
}
