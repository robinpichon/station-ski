<?php

namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use SkiBundle\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Form\FormError;

class RegisterController extends Controller
{
    /**
     * @Route("/register", name="register")
     */
    public function registerAction()
    {
        $request = Request::createFromGlobals();
        $passwordEncoder = $this->container->get('security.password_encoder');

        $user = new User();
        $form = $this->createFormBuilder()
            ->add('firstname', TextType::class)
            ->add('lastname', TextType::class)
            ->add('email', EmailType::class)
            ->add('password', RepeatedType::class, array('type' => PasswordType::class))
            ->add('save', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            if($this->captchaVerify($request->get('g-recaptcha-response')))
            {
                $data = $form->getData();
                $em = $this->getDoctrine()->getManager();
                $user->setFirstname($data['firstname'])
                    ->setLastname($data['lastname'])
                    ->setEmail($data['email'])
                    ->setPassword($passwordEncoder->encodePassword($user, $data['password']))
                    ->setRoles(['ROLE_USER']);

                $em->persist($user);
                $em->flush();

                $this->get('session')->getFlashBag()->add('success', 'Votre compte a été créé, vous pouvez désormais vous connecter.');
                return $this->redirectToRoute('login');
            } else {
                $form->addError(new FormError('Erreur lors de la vérification reCAPTCHA. Veuillez réésayer.'));
            }
        }

        $errors = $this->get('validator')->validate($form);
        return $this->render('UserBundle:Security:register.html.twig', [
          'form' =>  $form->createView(),
          'errors' => $errors
        ]);
    }

    function captchaVerify($recaptcha){
        $url = "https://www.google.com/recaptcha/api/siteverify";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, array(
            "secret" => "6LcpBDQUAAAAADef8ONsJM3Bip0drUQcz6MTOYjC", "response" => $recaptcha));
        $response = curl_exec($ch);
        curl_close($ch);
        $data = json_decode($response);
        return $data->success;
    }
}
