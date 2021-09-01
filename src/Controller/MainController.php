<?php

namespace App\Controller;

use App\Entity\Url;
use App\Form\UrlType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Process\Process;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'main', priority: 2)]
    public function index(Request $request): Response
    {
        $url = new Url();
        $form = $this->createForm(UrlType::class, $url);
        $form->handleRequest($request);

        return $this->render('Url/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/{url}', name: 'urlloader', priority: 1)]
    public function urlRoute(string $url = null): Response
    {
        /**
         * @var Url $redirect
         */
        $redirect = $this->getDoctrine()->getRepository(Url::class)->findOneBy(['shortUrl' => $url]);
        if($redirect)
        {
            // This should be listener, subscriber or separate task, so we can send redirect immediately
            $this->updateUrl($redirect);

            return $this->redirect($redirect->getLongUrl());
        }
        else
        {
            throw new BadRequestHttpException('Url not found!', null, 400);
        }
    }

    public function updateUrl(Url $url)
    {
        $em = $this->getDoctrine()->getManager();
        $url->updateAccess();
        $em->persist($url);
        $em->flush();
    }
}
