<?php
namespace SkiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use SkiBundle\Repository\StationRepository;
use SkiBundle\Repository\ReviewRepository;
use SkiBundle\Entity\Station;
use SkiBundle\Entity\Review;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class DetailController extends Controller
{
  /**
   * @Route("/station/{stationId}", name="detail_action")
   */
   public function detailAction($stationId)
   {
      $request = Request::createFromGlobals();
      $stationRepository = $this->getDoctrine()->getRepository(Station::class);
      $station = $stationRepository->findOneById($stationId);
      $reviews = $station->getReviews();
      //$moyenne = $this->getAverageNotation($reviews);

      $review = new Review();
      $form = $this->createFormBuilder()
          ->add('notation', IntegerType::class)
          ->add('comment', TextareaType::class)
          ->add('save', SubmitType::class)
          ->getForm();

      $form->handleRequest($request);
      if($form->isSubmitted() && $form->isValid()) {
          $data = $form->getData();

          $em = $this->getDoctrine()->getManager();
          $review->setUser($this->getUser())
                  ->setStation($station)
                  ->setNotation($data['notation'])
                  ->setComment($data['comment']);

          $em->persist($review);
          $em->flush();

          $this->get('session')->getFlashBag()->add('success', 'Commentaire enregistré avec succès.');
          return $this->redirect($request->getUri());
      }

      $sort = $request->query->get('sort');
      if(!empty($sort) && $sort === 'rating') {
          usort($reviews, function($a, $b) {
            return $a->getNotation() <=> $b->getNotation();
          });
      }

      if($station !== null) {
          return $this->render('SkiBundle:Main:detail.html.twig', array(
            'station' => $station,
            'reviews' => $reviews,
            //'moyenne' => $moyenne,
            'form' =>  $form->createView()
          ));
      } else {
          return $this->render('SkiBundle:Error:404.html.twig');
      }
   }

   /*public function getAverageNotation($reviews) {
      $total = 0;
      foreach ($reviews as $review) {
          $total += $review->getNotation();
      }
      $total = $total/count($reviews);
   }*/
}
