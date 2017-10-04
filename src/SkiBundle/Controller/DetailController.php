<?php
namespace SkiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use SkiBundle\Repository\StationRepository;
use SkiBundle\Repository\ReviewRepository;
use SkiBundle\Entity\Review;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DetailController extends Controller
{
  /**
   * @Route("/station/{stationId}", name="detail_action")
   */
   public function detailAction($stationId)
   {
      $stationRepository = new StationRepository();
      $station = $stationRepository->findById($stationId);

      $request = Request::createFromGlobals();
      $sort = $request->query->get('sort');
      if(!empty($sort) && $sort === 'rating') {
          $reviews = $station->getReviews();
          usort($reviews, function($a, $b) {
            return $a->getNotation() <=> $b->getNotation();
          });

          $station->setReviews($reviews);
      }

      if($station === null) {
          $response = new Response();
          //$response->setContent('<html><body><h1>Erreur 404</h1></body></html>');
          $response->setStatusCode(Response::HTTP_NOT_FOUND);
          $response->headers->set('Content-Type', 'text/html');
          return $response->send();
      }
      else
      {
          return $this->render('SkiBundle:Main:detail.html.twig', array(
            'station' => $station,
          ));
      }
   }

   /**
    * @Route("/review/{reviewId}", name="show_action")
    */
    public function showAction($reviewId)
    {
        $reviewRepository = $this->getDoctrine()->getRepository(Review::class);
        $review = $reviewRepository->find($reviewId);

        return $this->render('SkiBundle:Main:review.html.twig', array(
            'review' => $review,
        ));
    }
}
