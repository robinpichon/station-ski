<?php

namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use UserBundle\Form\AvatarType;
use SkiBundle\Entity\User;

class DefaultController extends Controller
{
    /**
     * @Route("/account", name="account")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $request = Request::createFromGlobals();
        $user = $this->getUser();

        $form_account = $this->createFormBuilder()
            ->add('firstname', TextType::class)
            ->add('lastname', TextType::class)
            ->add('email', EmailType::class)
            ->add('save', SubmitType::class)
            ->getForm();
        
        $form = $this->createForm(AvatarType::class, $user);

        $form_account->handleRequest($request);
        $form->handleRequest($request);

        if($form_account->isSubmitted() && $form_account->isValid()) {
            $data = $form_account->getData();

            $user->setFirstname($data['firstname'])
                 ->setLastname($data['lastname'])
                 ->setEmail($data['email']);

            $em->persist($user);
            $em->flush();

            $this->get('session')->getFlashBag()->add('green', 'Vos informations ont été mises à jour.');
        }

        if($form->isSubmitted() && $form->isValid()) {
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
          'form_account' => $form_account->createView(),
          'form' =>  $form->createView()
        ]);
    }
}
