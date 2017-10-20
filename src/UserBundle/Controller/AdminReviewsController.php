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

    /**
     * @Route("/admin/reviews/accept/{id}", name="admin_reviews_accept")
     */
    public function acceptAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $review = $em->getRepository(Review::class)->findOneById($id);
        $review->setStatus(true);
        $em->persist($review);
        $em->flush();

        $message = (new \Swift_Message('Votre avis sur '.$review->getStation()->getName().' a été accepté'))
                ->setFrom(['postmaster@h3r0x.ovh' => 'CriticSki'])
                ->setTo($this->getUser()->getEmail())
                ->setBody(
                    $this->renderView(
                        'Email/reviewAccepted.html.twig', [
                          'station' => $review->getStation(),
                          'user' => $this->getUser(),
                          'review' => $review
                        ]
                    ),
                    'text/html');

        $this->get('mailer')->send($message);
        $this->get('session')->getFlashBag()->add('green', 'Commentaire accepté.');
        return $this->redirectToRoute('admin_reviews');
    }

    /**
     * @Route("/admin/reviews/deny/{id}", name="admin_reviews_deny")
     */
    public function denyAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $review = $em->getRepository(Review::class)->findOneById($id);
        $em->remove($review);
        $em->flush();

        $message = (new \Swift_Message('Votre avis sur '.$review->getStation()->getName().' a été refusé'))
                ->setFrom(['postmaster@h3r0x.ovh' => 'CriticSki'])
                ->setTo($this->getUser()->getEmail())
                ->setBody(
                    $this->renderView(
                        'Email/reviewDenied.html.twig', [
                          'station' => $review->getStation(),
                          'user' => $this->getUser(),
                          'review' => $review
                        ]
                    ),
                    'text/html');

        $this->get('mailer')->send($message);
        $this->get('session')->getFlashBag()->add('green', 'Commentaire refusé.');
        return $this->redirectToRoute('admin_reviews');
    }
}
