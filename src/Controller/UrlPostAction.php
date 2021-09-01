<?php

namespace App\Controller;

use App\Entity\Url;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Hashids\Hashids;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class UrlPostAction extends AbstractFOSRestController
{
    public function __invoke(Request $request, LoggerInterface $logger): JsonResponse
    {
        $data = json_decode($request->getContent(),true);

        if(!isset($data['longUrl']))
        {
            $logger->error("Long url not specified", ['data' => $data, 'request' => $request]);
            throw new BadRequestHttpException('Long url not specified!', null, 429);
        }

        $longUrl = $this->normalizeUrl($data['longUrl']);

         $existingUrl = $this->getDoctrine()->getRepository(Url::class)->findOneBy(['longUrl' => $longUrl]);

        if($existingUrl)
        {
            $url = 'https://'. $request->getHost().'/'. $existingUrl->getShortUrl();
            return $this->json($url);
        }

        $shortUrl = $data['shortUrl'] ?? "";

        if (!preg_match('/^[A-Za-z0-9-]+$/', $shortUrl))
        {
            throw new BadRequestHttpException('Short Url can have letters, numbers and -', null, 429);
        }
        elseif ($this->isForbiddenShortUrl($shortUrl))
        {
            throw new BadRequestHttpException($shortUrl . ' is forbidden short url!', null, 444);
        }


        else
        {
            if(!($shortUrl && !$this->getDoctrine()->getRepository(Url::class)->findOneBy(['shortUrl' => $shortUrl])))
            {
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

        if(!str_starts_with($url, $UrlPrefixSecure) && !str_starts_with($url, $UrlPrefix)){
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