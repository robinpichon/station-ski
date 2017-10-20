<?php

namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use SkiBundle\Repository\ReviewRepository;
use SkiBundle\Entity\Review;

class AdminReviewsController extends Controller
{
    /**
     * @Route("/admin/reviews", name="admin_reviews")
     */
    public function indexAction()
    {
        $reviewsRepository = $this->getDoctrine()->getRepository(Review::class);
        $reviews = $reviewsRepository->findByStatus(false);

        return $this->render('UserBundle:Admin:reviews.html.twig', [
            'reviews' => $reviews
        ]);
    }
}
