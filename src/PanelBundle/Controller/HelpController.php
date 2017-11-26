<?php

namespace PanelBundle\Controller;

use PanelBundle\PanelBundle;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use PanelBundle\Command\EmailSender;

class HelpController extends Controller
{
    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/help", name = "help")
     */
    public function helpAction(Request $request)
    {
        $form = $this->createFormBuilder()
            ->add('tresc', TextareaType::class,
                array(
                    "label" => "Opis problemu: ",

                    'attr' => array(
                        'class' => 'form-control col-md-7 col-xs-12',
                        'placeholder'=>'Postaraj się opisać jak najdokładniej problem (co klikam, czego nie widać itp.) - ułatwi to rozwiązanie Twojego problemu)',
                    )
                )
            )
            ->add('save', SubmitType::class, array('label' => 'Wyślij', 'attr' => array('class' => 'btn btn-success')))
            ->getForm();
        $success = false;

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $mail = new EmailSender();
            $helpdesk  = 'Użytkownik '.$this->getUser()->__toString(). ' zgłasza problem: <br><br>'.$data['tresc']
            . '<br><br>'. $this->getUser()->getMail();


            $mail->CreateEmail('powiadomienia@krakus.net', 'Panel - problem', $helpdesk);
            $mail->SendEmail();

            $wiadomosc = 'Witaj '.$this->getUser()->__toString().' <br><br>Twoje zgłoszenie zostało wysłane do naszego Administratora. Postaramy się jak najszybciej rozwiązać problem.';

            $mail->CreateEmail($this->getUser()->getMail(), 'Panel - problem', $wiadomosc);
            $mail->SendEmail();
            $success = true;
        }

        return $this->render('PanelBundle:Help:help.html.twig', array('form' => $form->createView(), 'success'=>$success));
    }
}
