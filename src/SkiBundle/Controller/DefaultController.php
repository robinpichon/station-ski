<?php

namespace SkiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="index_action")
     */
    public function indexAction()
    {
        return $this->render('SkiBundle:Main:index.html.twig');
    }
}
