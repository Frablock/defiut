<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class BaseController extends AbstractController
{
    //  '^(?!api).*' = explude all URL that start with /api/
    #[Route('/{reactRoute}', name: 'react_app', requirements: ['reactRoute' => '^(?!api).*'], defaults: ['reactRoute' => null])]
    public function app()
    {
        return $this->render('index/index.html.twig');
    }
}
