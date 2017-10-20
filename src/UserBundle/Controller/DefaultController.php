<?php

namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use SkiBundle\Entity\User;
use UserBundle\Form\AvatarType;

class DefaultController extends Controller
{
    /**
     * @Route("/account", name="account")
     */
    public function indexAction()
    {
        $request = Request::createFromGlobals();

        $user = $this->getUser();
        $form = $this->createForm(AvatarType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
            $file = $user->getAvatarFile();
            $fileName = md5(uniqid()).'.'.$file->guessExtension();

            if($user->getAvatar() !== 'default.png') {
                unlink($this->getParameter('avatars_directory').'/'.$user->getAvatar());
            }

            $file->move(
                $this->getParameter('avatars_directory'),
                $fileName
            );

            $user->setAvatarFile(null);
            $user->setAvatar($fileName);

            $em->persist($user);
            $em->flush();

            $this->get('session')->getFlashBag()->add('green', 'Avatar mis à jour.');
        }
        else if($form->isSubmitted() && !$form->isValid())
        {
            $this->get('session')->getFlashBag()->add('red', 'Format JPEG, PNG ou GIF uniquement.');
        }

        return $this->render('UserBundle:Default:index.html.twig', [
          'form' =>  $form->createView()
        ]);
    }
}
