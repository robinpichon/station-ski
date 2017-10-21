<?php
namespace SkiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use SkiBundle\Repository\StationRepository;
use SkiBundle\Repository\ReviewRepository;
use SkiBundle\Entity\Station;
use SkiBundle\Entity\Review;
use SkiBundle\Resources\Map\MapService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
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

      if($station !== null) {
          $reviews = [];
          $user_reviewed = false;

          foreach($station->getReviews() as $review) {
              if($review->getStatus()) {
                  array_push($reviews, $review);
              }
              if($this->getUser() != null && $review->getUser()->getId() === $this->getUser()->getId()) {
                  $user_reviewed = true;
              }
          }

          $moyenne = $this->getAverageNotation($reviews);
          $ratio = $this->getNotationRatio($reviews);
      }

      $form = $this->createFormBuilder()
          ->add('notation', RangeType::class)
          ->add('comment', TextareaType::class)
          ->add('save', SubmitType::class)
          ->getForm();

      $form->handleRequest($request);
      if($form->isSubmitted() && $form->isValid()) {
          $data = $form->getData();

          $review = new Review();
          $em = $this->getDoctrine()->getManager();
          $review->setUser($this->getUser())
                  ->setStation($station)
                  ->setStatus(false)
                  ->setNotation($data['notation'])
                  ->setComment($data['comment']);

          $em->persist($review);
          $em->flush();

          $this->get('session')->getFlashBag()->add('green', 'Commentaire envoyé avec succès.<br>Vous recevrez un mail lorsqu\'il sera validé.');
          return $this->redirect($request->getUri());
      }

      if($station !== null) {
          $map = $this->container->get('station.map.generate');
          return $this->render('SkiBundle:Main:detail.html.twig', [
            'station' => $station,
            'reviews' => $reviews,
            'moyenne' => $moyenne,
            'ratio' => $ratio['positive'],
            'user_reviewed' => $user_reviewed,
            'mapurl' => $map->getMapUrl($station->getId()),
            'form' =>  $form->createView()
          ]);
      } else {
          return $this->render('SkiBundle:Exception:error404.html.twig');
      }
   }

   public function getAverageNotation($reviews) {
      if(count($reviews) < 1) return false;
       $moyenne = 0;
       foreach($reviews as $review) {
           $moyenne += $review->getNotation();
       }
       $moyenne /= count($reviews);
       return $moyenne;
   }

   public function getNotationRatio($reviews) {
     if(count($reviews) < 1) return false;
      $ratio = ['positive' => 0, 'negative' => 0];
      foreach($reviews as $review) {
          if($review->getNotation() > 2.5) $ratio['positive']++;
          else $ratio['negative']++;
      }
      $ratio = [
        'positive' => ($ratio['positive']*100)/count($reviews),
        'negative' => ($ratio['negative']*100)/count($reviews)
      ];
      return $ratio;
   }
}
