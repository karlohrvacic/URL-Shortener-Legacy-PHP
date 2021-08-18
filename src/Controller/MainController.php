<?php

namespace App\Controller;

use App\Entity\Url;
use App\Form\URLType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'main', priority: 2)]
    public function index(Request $request): Response
    {
        $url = new Url();
        $form = $this->createForm(URLType::class, $url);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $request = Request::create(
                '/api/shorten',
                'POST',
                [
                    'longURL' => $url->getLongUrl(),
                    'shortURL' => $url->getShortUrl()
                ]
            );
            $request->headers->set('content-type', 'application/json');
            $request->overrideGlobals();
            $request->initialize();

        }

        return $this->render('URL/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/{url}', name: 'urlloader', priority: 1)]
    public function urlRoute(string $url = null): Response
    {
        $redirect = $this->getDoctrine()->getRepository(Url::class)->findOneBy(['shortURL' => $url]);
        if($redirect){
            return $this->redirect($redirect->getLongUrl());
        } else{
            throw new BadRequestHttpException('Url not found!', null, 400);
        }
    }
}
