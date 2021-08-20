<?php

namespace App\Controller;

use App\Entity\Url;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Hashids\Hashids;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class UrlPostAction extends AbstractFOSRestController
{
    public function __invoke(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(),true);

        if(!isset($data['longUrl'])){
            throw new BadRequestHttpException('UrlResource not specified!', null, 429);
        }

        $longUrl = $this->normalizeUrl($data['longUrl']);

        $shortUrl = $data['shortUrl'] ?? "";
        if ($this->isForbiddenShortUrl($shortUrl)){
            throw new BadRequestHttpException($shortUrl . ' is forbidden short UrlResource!', null, 429);
        }

        $existingUrl = $this->getDoctrine()->getRepository(Url::class)->findOneBy(['longUrl' => $longUrl]);

        if($existingUrl){
            $url = 'https://'. $request->getHost().'/'. $existingUrl->getShortUrl();
            return $this->json($url);
        }
        else{
            if(!($shortUrl && !$this->getDoctrine()->getRepository(Url::class)->findOneBy(['shortUrl' => $shortUrl]))){
                $hashID = new Hashids('UrlResource-Shortener', 5);
                $shortUrl = $hashID->encode(rand(), rand());
            }

            $newUrl = new Url();
            $newUrl->setLongUrl($longUrl);
            $newUrl->setShortUrl($shortUrl);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($newUrl);
            $entityManager->flush();
        }

        $url = 'https://'. $request->getHost().'/'. $newUrl->getShortUrl();
        return $this->json($url);
    }

    private function normalizeUrl(string $url) : string
    {
        $UrlPrefixSecure = 'https://';
        $UrlPrefix = 'http://';

        if(!str_starts_with($url, $UrlPrefixSecure) || !str_starts_with($url, $UrlPrefix)){
            $url = $UrlPrefixSecure.$url;
        }

        return $url;

    }
    private function isForbiddenShortUrl(string $shortUrl) : bool
    {
        $forbiddenWords = array('api', 'getUrl', 'info', 'shorten', '_error', 'UrlResource');
        return in_array($shortUrl, $forbiddenWords);
    }
}