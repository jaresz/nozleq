<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Domyślny kontroler dla www
 * @author jaresz
 */

class DefaultController extends Controller
{
    /**
     * Kontroler strony domowej
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // strona główna
        return $this->render('default/index.html.twig', array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
        ));
    }
    /**
     * Kontroler tymczasowy do testowania logowania
     * @Route("/hello", name="hello")
     */
    public function helloAction(Request $request)
    {
    	// strona dla zalogowanych użytkowników
    	return $this->render('default/4loggedMockup.html.twig', array(
    			'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
    	));
    }
}
