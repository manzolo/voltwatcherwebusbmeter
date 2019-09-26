<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use \Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Device;
use App\Entity\Log;

class DefaultController extends AbstractController
{
    /**
     * Matches / exactly
     *
     * @Route("/", name="welcome")
     */
    public function index(Request $request)
    {
        return new Response("Welcome!");
    }
}
