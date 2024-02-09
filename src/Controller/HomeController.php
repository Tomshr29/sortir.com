<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
#[Route('/', name: 'main_')]
class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(): Response
    {
        return $this->render('main/home.html.twig');
    }

    #[Route('/test', name: 'test')]
    public function test(): Response
    {
        echo 'Test';
        //die();
        return $this->render('');
    }
}