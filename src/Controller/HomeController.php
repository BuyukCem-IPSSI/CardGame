<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class HomeController
 * @package App\Controller
 */
class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function nav()
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
	/**
	 * @Route("/homepage", name="homepage")
	 */
	public function index()
	{
		return $this->render('home/home.html.twig', [
			'controller_name' => 'HomeController',
		]);
	}



}
