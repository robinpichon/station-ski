<?php
namespace SkiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use SkiBundle\Repository\ReviewRepository;
use SkiBundle\Entity\Review;

class AddReviewController extends Controller
{
  /**
   * @Route("/review/create/{stationId}/{userId}/{notation}/{comment}", name="station_create")
   */
  public function createAction($stationId, $userId, $notation, $comment)
  {
      $review = new Review();
      $review->setStationId($stationId);
      $review->setUserId($userId);
      $review->setNotation($notation);
      $review->setComment($comment);

      $em = $this->getDoctrine()->getManager();
      $em->persist($review); // Mettre dans une file d'attente
      $em->flush(); // Inserer les objets en file d'attente dans la base de données

      die('Ok');
  }
}
