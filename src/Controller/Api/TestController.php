<?php
/**
 * Created by PhpStorm.
 * User: clem
 * Date: 11/12/18
 * Time: 16:42
 */

namespace App\Controller\Api;


use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class TestController
{
    /**
     * @param Request $request
     * @param string $name
     * @return View
     * @Rest\Get("/test/{name}")
     */
    public function getAccount(Request $request, string $name) : View {
        return View::create(["Hello ".$name], Response::HTTP_ACCEPTED);
    }

}