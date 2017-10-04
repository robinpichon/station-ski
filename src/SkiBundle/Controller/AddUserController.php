<?php
namespace SkiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use SkiBundle\Repository\UserRepository;
use SkiBundle\Entity\User;

class AddUserController extends Controller
{
  /**
   * @Route("/account/register", name="user_create")
   */
  public function createAction()
  {
      // Make it dynamic...
      $firstname = 'John';
      $lastname = 'Doe';
      $email = 'john.doe@web.com';
      $pass = md5('helloworld');

      $user = new User();
      $user->setFirstname($firstname);
      $user->setLastname($lastname);
      $user->setEmail($email);
      $user->setPass($pass);

      $em = $this->getDoctrine()->getManager();
      $em->persist($user); // Mettre dans une file d'attente
      $em->flush(); // Inserer les objets en file d'attente dans la base de données

      die('Ok');
  }
}
