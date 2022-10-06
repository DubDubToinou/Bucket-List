<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/"  ,name="main_")
 */
class MainController extends AbstractController
{
    /**
     * @Route("" , name="home")
     */
    public function home(): Response
    {
        return $this->render('main/home.html.twig');
    }

    /**
     * @Route("/AboutUs" , name="aboutus")
     */
    public function aboutUs(): Response
    {
        $team = file_get_contents('../data/team.json');
        $json_data = json_decode($team, true);

        return $this->render('main/aboutus.html.twig', [
            'team' => $json_data,
        ]);
    }
}
