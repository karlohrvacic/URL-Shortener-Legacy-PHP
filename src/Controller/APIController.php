<?php

namespace App\Controller;

use App\Entity\Url;
use Hashids\Hashids;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;


class APIController extends AbstractFOSRestController
{
    #[Route('/api/info/{shortURL}', name: 'getURL', methods: 'GET')]
    public function fetchURL(Request $request, string $shortURL = null): Response
    {
        $redirect = $this->getDoctrine()->getRepository(Url::class)->findOneBy(['shortURL' => $shortURL]);
        if($redirect){
            return $this->render('URL/URLInfo.html.twig', [
                'url' => $redirect,
            ]);
        }
        else{
            throw new BadRequestHttpException('Url not found!', null, 418);
        }
    }


    #[Route('/api/shorten', name: 'submitURL', methods: 'POST')]
    public function shortenURL(Request $request): Response
    {
        $data = json_decode($request->getContent(),true);

        if(!isset($data['longURL'])){
            throw new BadRequestHttpException('Url not specified!', null, 429);
        }
        
        $longURL = $this->normalizeURL($data['longURL']);

        $shortURL = $data['shortURL'] ?? "";

        if ($this->isForbidenShortURL($shortURL)){
            throw new BadRequestHttpException($shortURL . ' is forbidden short Url!', null, 429);
        }

        $existingURL = $this->getDoctrine()->getRepository(Url::class)->findOneBy(['longURL' => $longURL]);

        if($existingURL){
            $url = 'https://'. $request->getHost().'/'. $existingURL->getShortUrl();
            return $this->json($url);
        }
        else{
            if(!($shortURL && !$this->getDoctrine()->getRepository(Url::class)->findOneBy(['shortURL' => $shortURL]))){
                $hashID = new Hashids('Url-Shortener', 5);
                $shortURL = $hashID->encode(rand(), rand());
            }

            $newURL = new Url();
            $newURL->setLongUrl($longURL);
            $newURL->setShortUrl($shortURL);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($newURL);
            $entityManager->flush();
        }

        $url = 'https://'. $request->getHost().'/'. $newURL->getShortUrl();
        return $this->json($url);
    }

    private function normalizeURL(string $url) : string
    {
        $URLprefixSecure = 'https://';
        $URLprefix = 'http://';

        if(!str_starts_with($url, $URLprefixSecure) || !str_starts_with($url, $URLprefix)){
            $url = $URLprefixSecure.$url;
        }

        return $url;

    }
    private function isForbidenShortURL(string $shortURL) : bool
    {
        $forbidenWords = array('api', 'getURL', 'info', 'shorten', '_error', 'Url');
        return in_array($shortURL, $forbidenWords);
    }
}
