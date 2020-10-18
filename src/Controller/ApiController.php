<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    /**
     * @Route("/api/test", name="api")
     */
    public function index()
    {
        return $this->json(['message' => 'test ok']);
    }
}
