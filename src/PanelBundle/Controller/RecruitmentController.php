<?php

namespace PanelBundle\Controller;

use PanelBundle\Command\EmailSender;
use PanelBundle\Entity\Osoby;
use PanelBundle\Constants\Plec;
use PanelBundle\Constants\Sekcja;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Nzo\UrlEncryptorBundle\Annotations\ParamDecryptor;

class RecruitmentController extends Controller
{
    /**
     * @Route("/register", name = "register")
     */
    public function registerAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $user = new Osoby();
        $form = $this->createFormBuilder($user)
            ->add('imie', TextType::class, array("label" => "Imię: ", 'attr' => array('class' => 'form-control col-md-7 col-xs-12')))
            ->add('nazwisko', TextType::class, array('label' => 'Nazwisko:', 'attr' => array('class' => 'form-control col-md-7 col-xs-12')))
            ->add('plec', ChoiceType::class, array('label' => 'Płeć:', 'expanded' => true, 'choices' => Plec::choiceTab()))
            ->add('dataUrodzenia', DateType::class, array('label' => 'Data urodzenia:', 'widget' => 'single_text', 'format' => 'dd/MM/yyyy', 'attr' => array('class' => 'form-control', 'data-inputmask' => "'mask': '99/99/9999'", 'placeholder' => 'dd/mm/rrrr')))
            ->add('telefon', TextType::class, array('label' => 'Telefon:', 'attr' => array('class' => 'form-control col-md-7 col-xs-12', 'data-inputmask' => "'mask': '999-999-999'")))
            ->add('mail', TextType::class, array('label' => 'Mail:', 'attr' => array('class' => 'form-control col-md-7 col-xs-12')))
            ->add('wzrost', TextType::class, array('label' => 'Wzrost:', 'attr' => array('class' => 'form-control col-md-7 col-xs-12', 'placeholder' => 'cm')))
            ->add('uczelnia', TextType::class, array('label' => 'Uczelnia:', 'attr' => array('class' => 'form-control col-md-7 col-xs-12', 'placeholder' => 'pełna nazwa')))
            ->add('wydzial', TextType::class, array('label' => 'Wydział:', 'attr' => array('class' => 'form-control col-md-7 col-xs-12', 'placeholder' => 'pełna nazwa')))
            ->add('rok', TextType::class, array('label' => 'Rok studiów (klasa):', 'attr' => array('class' => 'form-control col-md-7 col-xs-12', 'placeholder' => 'liczba')))
            ->add('opis', TextareaType::class, array('label' => 'Dotychczasowy kontakt z tańcem lub śpiewem oraz dlaczego chcesz tańczyć w Krakusie?:', 'attr' => array('class' => 'form-control col-md-7 col-xs-12')))
            ->add('sekcja', ChoiceType::class, array('label' => 'Którymi sekcjami byłbyś zainteresowany?:', 'multiple' => true, 'expanded' => true, 'choices' => Sekcja::choiceTab()))
            ->add('save', SubmitType::class, array('label' => 'Zapisz', 'attr' => array('class' => 'btn btn-success')))
            ->getForm();


        $form->handleRequest($request);
        $error = false;
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $user->setLogin($em->getRepository('PanelBundle:Osoby')->createLogin($user->getImie(), $user->getNazwisko()));
            $user->setHaslo(\PanelBundle\Model\ChangePassword::generujHaslo());
            $user->setRole(array('ROLE_USER'));
            $user->setSekcja($this->get('panel.sekcja')->setUprawnienia($user->getSekcja()));
            $user->setStatus($this->get('panel.status')->choiceTab()['Nabór']);
            $em->persist($user);
            $mail = new EmailSender();
            $wiadomosc = 'Witaj ' . $user->__toString() . '<br><br>Twoje dane zostały pomyślnie zapisane w naszym systemie. Do zobaczenia na przesłuchaniu.';
            $mail->CreateEmail($user->getMail(), 'Rejestracja przebiegła pomyślnie', $wiadomosc);
            $mail->SendEmail();
            $em->flush();

            $error = true;
        }

        return $this->render('PanelBundle:Recruitment:form.html.twig', array('form' => $form->createView(), 'error' => $error));
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/recruitment", name = "recruitment")
     */
    public function recruitmentAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('PanelBundle:Osoby')->findBy(array('status' => $this->get('panel.status')->choiceTab()['Nabór']));
        $user = new Osoby();
        $form = $this->createFormBuilder()
            ->add('tresc', TextareaType::class, array('label' => 'Treść widomości', 'attr' => array('class' => 'form-control col-md-7 col-xs-12')))
            ->add('save', SubmitType::class, array('label' => 'Zapisz', 'attr' => array('class' => 'btn btn-success')))
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $mail = new EmailSender();
            $adresy = $em->getRepository('PanelBundle:Osoby')->findRecruitToNotificate();
            $wiadomosc = 'Witaj, <br><br>';
            $wiadomosc .= $data['tresc'];
            $mail->CreateEmail('powiadomienia@krakus.net', 'Nowa wiadomość od kierownictwa Zespołu', $wiadomosc, $adresy);
            $mail->SendEmail();
            $this->redirectToRoute('recruitment');
        }

        return $this->render('PanelBundle:Recruitment:recruitment.html.twig', array('entities' => $entities, 'form' => $form->createView()));
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/recruitDel/{id}", name = "recruitDel")
     * @ParamDecryptor(params={"id"})
     */
    public function recruitDeltAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('PanelBundle:Osoby')->find($id);
        $em->remove($entities);
        $em->flush();

        return $this->redirectToRoute('recruitment');
    }
}
