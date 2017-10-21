<?php

namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use SkiBundle\Repository\UserRepository;
use SkiBundle\Repository\ReviewRepository;
use SkiBundle\Entity\User;
use SkiBundle\Entity\Review;

class AdminUsersController extends Controller
{
    /**
     * @Route("/admin/users", name="admin_users")
     */
    public function indexAction()
    {
        $usersRepository = $this->getDoctrine()->getRepository(User::class);
        $users = $usersRepository->findAll();

        return $this->render('UserBundle:Admin:users.html.twig', [
            'users' => $users
        ]);
    }

    /**
     * @Route("/admin/users/edit/{id}", name="admin_users_edit")
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->findOneById($id);

        $em->persist($user);
        $em->flush();

        $this->get('session')->getFlashBag()->add('green', 'Membre mis à jour.');
        return $this->redirectToRoute('admin_users');
    }

    /**
     * @Route("/admin/users/delete/{id}", name="admin_users_delete")
     */
    public function deleteAction($id)
    {
        if($id == $this->getUser()->getId()) {
            $this->get('session')->getFlashBag()->add('red', 'Vous ne pouvez pas supprimer votre propre compte.');
        } else {
            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository(User::class)->findOneById($id);
            $user_reviews = $em->getRepository(Review::class)->findByUser($user);

            foreach ($user_reviews as $review) {
                $em->remove($review);
            }

            $em->remove($user);
            $em->flush();

            $this->get('session')->getFlashBag()->add('green', 'Membre supprimé.');
        }

        return $this->redirectToRoute('admin_users');
    }
}
