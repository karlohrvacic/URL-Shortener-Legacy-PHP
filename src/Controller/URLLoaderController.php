<?php

namespace App\Controller;

use App\Entity\URL;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

class URLLoaderController extends AbstractController
{
    #[Route('/{URL}', name: 'urlloader')]
    public function index(string $URL = null): Response
    {
        dd($URL);
        $redirect = $this->getDoctrine()->getRepository(URL::class)->findOneBy(['shortURL' => $URL]);
        if($redirect){
            return $this->redirect($redirect->getLongURL());
        } else{
            throw new BadRequestHttpException('URL not found!', null, 400);
        }
    }
}
