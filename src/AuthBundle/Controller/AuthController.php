<?php

namespace AuthBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AuthController extends Controller
{
    public function indexAction()
    {
        return $this->render('AuthBundle::homepage.html.twig', ['user' => $this->getUser()]);
    }
}
