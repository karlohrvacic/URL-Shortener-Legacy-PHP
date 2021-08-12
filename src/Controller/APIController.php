<?php

namespace App\Controller;

use App\Entity\URL;
use Hashids\Hashids;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class APIController extends AbstractController
{
    #[Route('/url/info/{shortURL}', name: 'getURL')]
    public function fetchURL(Request $request, string $shortURL = null): Response
    {
        $redirect = $this->getDoctrine()->getRepository(URL::class)->findOneBy(['shortURL' => $shortURL]);
        if($redirect){
            return $this->json($redirect);
        }
        else{
            throw new BadRequestHttpException('URL not found!', null, 418);
        }
    }

    #[Route('/url/shorten/{longURL}/{shortURL}', name: 'submitURL')]
    public function shortenURL(Request $request, string $longURL = null, string $shortURL = null): Response
    {
        if(!$longURL){
            throw new BadRequestHttpException('URL not specified!', null, 429);
        }
        $URLexists = $this->getDoctrine()->getRepository(URL::class)->findOneBy(['longURL' => $longURL]);
        if($URLexists){
            return $this->json($URLexists);
        }
        else{
            if(!($shortURL && !$this->getDoctrine()->getRepository(URL::class)->findOneBy(['shortURL' => $shortURL]))){
                $hashID = new Hashids('URL-Shortener', 5);
                $shortURL = $hashID->encode(rand() * rand());
            }
            $URLprefix = 'https://';
            if(!str_starts_with($longURL, $URLprefix)){
                $longURL = $URLprefix.$longURL;
            }

            $newURL = new URL();
            $newURL->setLongURL($longURL);
            $newURL->setShortURL($shortURL);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($newURL);
            $entityManager->flush();
        }
        return $this->json($newURL);
    }
}
