<?php
namespace SkiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use SkiBundle\Repository\StationRepository;
use SkiBundle\Repository\ReviewRepository;
use SkiBundle\Entity\Station;
use SkiBundle\Entity\Review;
use Symfony\Component\HttpFoundation\Request;

class ListController extends Controller
{
  /**
   * @Route("/station", name="list_action")
   */
   public function listAction()
   {
      $stationRepository = $this->getDoctrine()->getRepository(Station::class);
      $stations = $stationRepository->findAll();

      return $this->render('SkiBundle:Main:list.html.twig', array(
        'stations' => $stations
      ));
   }
}
