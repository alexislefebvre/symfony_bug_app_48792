<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route(
        '/about',
        name: 'true_badges_about'
    )]
    public function aboutAction()
    {
        return $this->render('about.html.twig');
    }
}
