<?php

namespace PanelBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Validator\Constraints\Date;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class DefaultController extends Controller
{
    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/", name = "index")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        if ($this->isGranted('ROLE_ADMIN')) {
            $entities = $em->getRepository('PanelBundle:Aktualnosci')->findCurrentNews(new \DateTime(), $this->getUser()->getStatus());
        } else {
            $entities = $em->getRepository('PanelBundle:Aktualnosci')->findCurrentNews(new \DateTime(), $this->getUser()->getStatus(), 1);
        }


        return $this->render('PanelBundle:News:news.html.twig', array('entities' => $entities));
    }

    /**
     * @Route("/login", name = "login")
     */
    public function loginAction()
    {
        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('PanelBundle::logowanie.html.twig', array(
            // last username entered by the user
            'last_username' => $lastUsername,
            'error' => $error,
        ));
        //return $this->render('PanelBundle::login.html.twig');
    }

    /**
     * @Route("/logout", name = "logout")
     */
    public function logoutAction()
    {
        // return $this->render('PanelBundle::logout.html.twig');
        $this->redirectToRoute('login');
    }

    /**
     * @Route("/test", name = "test")
     */
    public function testAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $em->getRepository('PanelBundle:Osoby')->findPassportExpiration($this->getUser()->getId());
         return $this->render('PanelBundle:gentelella:general_elements.html.twig');
        //$this->redirectToRoute('login');
    }
}
