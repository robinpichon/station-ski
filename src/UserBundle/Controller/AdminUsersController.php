<?php

namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class AdminUsersController extends Controller
{
    /**
     * @Route("/admin/users", name="admin_users")
     */
    public function indexAction()
    {
        return $this->render('UserBundle:Admin:users.html.twig');
    }
}
