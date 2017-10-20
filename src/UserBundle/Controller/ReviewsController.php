<?php

namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use SkiBundle\Repository\ReviewRepository;
use SkiBundle\Entity\Review;

class ReviewsController extends Controller
{
    /**
     * @Route("/account/reviews", name="account_reviews")
     */
    public function indexAction()
    {
        $reviewsRepository = $this->getDoctrine()->getRepository(Review::class);
        $reviews = $reviewsRepository->findByUser($this->getUser());

        return $this->render('UserBundle:Default:reviews.html.twig', [
            'reviews' => $reviews
        ]);
    }

    /**
     * @Route("/account/reviews/delete/{id}", name="account_reviews_delete")
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $review = $em->getRepository(Review::class)->findOneById($id);

        if($review && $review->getUser()->getId() === $this->getUser()->getId())
        {
            $em->remove($review);
            $em->flush();
            $this->get('session')->getFlashBag()->add('green', 'Commentaire supprimé avec succès.');
        }
        else
        {
            $this->get('session')->getFlashBag()->add('red', 'Erreur lors de la suppression du commentaire.');
        }

        return $this->redirectToRoute('account_reviews');
    }
}
