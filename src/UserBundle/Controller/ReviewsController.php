<?php

namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class ReviewsController extends Controller
{
    /**
     * @Route("/account/reviews", name="account_reviews")
     */
    public function indexAction()
    {
        return $this->render('UserBundle:Default:reviews.html.twig');
    }
}
