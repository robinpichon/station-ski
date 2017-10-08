<?php
namespace SkiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use SkiBundle\Repository\StationRepository;
use SkiBundle\Repository\ReviewRepository;
use SkiBundle\Entity\Station;
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
      $stationRepository = $this->getDoctrine()->getRepository(Station::class);
      $station = $stationRepository->findOneById($stationId);

      $reviewRepository = $this->getDoctrine()->getRepository(Review::class);
      $reviews = $reviewRepository->getReviews($stationId);

      $request = Request::createFromGlobals();
      $sort = $request->query->get('sort');
      if(!empty($sort) && $sort === 'rating') {
          //$reviews = $station->getReviews();
          usort($reviews, function($a, $b) {
            return $a->getNotation() <=> $b->getNotation();
          });

          //$station->setReviews($reviews);
      }

      if($station !== null) {
          return $this->render('SkiBundle:Main:detail.html.twig', array(
            'station' => $station,
            'reviews' => $reviews
          ));
      } else {
          return $this->render('SkiBundle:Error:404.html.twig');
      }
   }
}
