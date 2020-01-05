<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home(){
        return $this->render('homepage.html.twig', ['title' => 'Homepage']);
    }

    /**
     * @Route("/admin", name="app_admin_page")
     */
    public function admin(){
        return $this->render('homepage.html.twig', ['title' => 'Admin page']);
    }

    /**
     * @Route("/profile", name="app_profile_page")
     */
    public function profile(){
        return $this->render('homepage.html.twig', ['title' => 'Page only for authenticated users']);
    }
}