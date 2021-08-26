<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Url;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class UrlGetAction extends AbstractFOSRestController
{
    public function __invoke(Request $request): JsonResponse
    {

        dd($request);

        $redirect = $this->getDoctrine()->getRepository(Url::class)->findOneBy(['shortUrl' => $shortUrl]);

        if($redirect){
            return $this->json($redirect);
        }
        else{
            throw new BadRequestHttpException('Url not found!', null, 418);
        }
    }
}