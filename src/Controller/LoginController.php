<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class LoginController extends AbstractController
{
    #[Route('/', name: 'app')]
    public function app()
    {

        return $this->render('base.html.twig');
        //return new JsonResponse('',200);
    }
}
