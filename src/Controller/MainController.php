<?php

namespace App\Controller;

use App\Entity\URL;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'urlloader', priority: 2)]
    public function index(): Response
    {
        return $this->json('Welcome to URL API.');
    }

    #[Route('/{URL}', name: 'urlloader')]
    public function urlRoute(string $URL = null): Response
    {
        $redirect = $this->getDoctrine()->getRepository(URL::class)->findOneBy(['shortURL' => $URL]);
        if($redirect){
            return $this->redirect($redirect->getLongURL());
        } else{
            throw new BadRequestHttpException('URL not found!', null, 400);
        }
    }
}
