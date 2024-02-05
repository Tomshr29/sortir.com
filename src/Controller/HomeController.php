<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController
{
    #[Route('/')]
    public function index(): Response
    {
        return new Response(
            '<html lang="fr"><body><h1>Hello world!</h1></body></html>'
        );
    }
}