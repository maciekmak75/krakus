<?php

namespace PanelBundle\Controller;

use PanelBundle\Command\EmailSender;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use PanelBundle\Entity\Aktualnosci;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Request;
use Nzo\UrlEncryptorBundle\Annotations\ParamDecryptor;

class NewsController extends Controller
{
    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/newsEdit/{idNews}", name = "newsEdit")
     * @ParamDecryptor(params={"idNews"})
     */
    public function newsEditAction(Request $request, $idNews = false)
    {
        if ($idNews == $this->get('nzo_url_encryptor')->decrypt(false)) {
            $idNews = false;
        }
        $em = $this->getDoctrine()->getManager();
        $news = $idNews ? $em->getRepository('PanelBundle:Aktualnosci')->find($idNews) : new Aktualnosci();
        $osobyBaza = $em->getRepository('PanelBundle:Osoby')->findBy(array('status'=>2));
        $osoby = [];
        for ($i = 0; $i < count($osobyBaza); $i++) {
            if (!in_array($osobyBaza[$i]->_toString(), $osoby)) {
                $osoby[$osobyBaza[$i]->_toString()] = $em->getReference('PanelBundle:Osoby', $osobyBaza[$i]->getId());
            }
        }
        ksort($osoby);
        if ($idNews) {
            $title = 'Edycja';
        } else {
            $title = 'Dodawanie';
        }

        $form = $this->createFormBuilder()
            ->add('tytul', TextType::class, array("label" => "Tytuł: ", 'attr' => array('class' => 'form-control col-md-7 col-xs-12'), 'data' => $idNews ? ($news->getTytul()) : ''))
            ->add('tresc', TextareaType::class, array('label' => 'Treść:', 'attr' => array('class' => 'form-control col-md-7 col-xs-12'), 'data' => $idNews ? ($news->getTresc()) : ''))
            ->add('dataOd', DateType::class, array('label' => 'Data publikacji:', 'widget' => 'single_text', 'format' => 'dd/MM/yyyy', 'attr' => array('class' => 'form-control', 'data-inputmask' => "'mask': '99/99/9999'", 'placeholder' => 'dd/mm/rrrr'), 'data' => $idNews ? ($news->getDataOd()) : new \DateTime()))
            ->add('dataDo', DateType::class, array('label' => 'Data końca wyświetlania:', 'widget' => 'single_text', 'format' => 'dd/MM/yyyy', 'data' => $idNews ? ($news->getDataDo()) : new \DateTime('+7day'), 'attr' => array('class' => 'form-control', 'data-inputmask' => "'mask': '99/99/9999'", 'placeholder' => 'dd/mm/rrrr')))
            ->add('status', ChoiceType::class, array('label' => 'Dla kogo:', 'expanded' => true, 'multiple' => true, 'data' => $idNews ? $this->get('panel.status')->getStatus($news->getStatus()) : $this->get('panel.status')->getStatus(7), 'choices' => $this->get('panel.status')->choiceNewsTab()))
            ->add('osoby', ChoiceType::class, array('label' => 'Osoby:', 'multiple' => true, 'choices' => $osoby, 'attr' => array( 'multiple'=>"multiple",'class' => 'select2_multiple form-control')))
            ->add('save', SubmitType::class, array('label' => 'Zapisz', 'attr' => array('class' => 'btn btn-success')))
            ->getForm();


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $news->setIdUzytkownik($em->getReference('PanelBundle:Osoby', $this->getUser()->getId()));
            $news->setDataGeneracji(new \DateTime());
            $news->setStatus($this->get('panel.status')->setUprawnienia($data['status']));
            $news->setDataDo($data['dataDo']);
            $news->setDataOd($data['dataOd']);
            $news->setTytul($data['tytul']);
            $tresc = $data['tresc'];
            $tresc .= PHP_EOL;
            for($i=0;$i<count($data['osoby']);$i++){
                if ($i  == count($data['osoby']) -1) {
                    $tresc .= $data['osoby'][$i]->_toString();
                    break;
                }
                $tresc .= $data['osoby'][$i]->_toString().PHP_EOL;
            }
            $news->setTresc($tresc);
            $mail = new EmailSender();
            $wiadomosc = 'Witaj, <br><br> W panelu pojawiły się nowe informacje od '.
                $news->getIdUzytkownik()->__toString().':<br><br><b>'.
                $news->getTytul().'</b><br><br>'.
                $news->getTresc();
            $adresy = $em->getRepository('PanelBundle:Osoby')->findPersonToNotificate($news->getStatus());

            $mail->CreateEmail('powiadomienia@krakus.net', 'Nowe informacje', nl2br($wiadomosc), $adresy);
            $em->persist($news);
            $em->flush();
            $mail->SendEmail();
            return $this->redirectToRoute('homepage');
        }
        return $this->render('PanelBundle:News:newsEdit.html.twig', array('form' => $form->createView(), 'title' => $title));

    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/newsDel/{idNews}", name = "newsDel")
     * @ParamDecryptor(params={"idNews"})
     */
    public function newsDelAction($idNews)
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('PanelBundle:Aktualnosci')->find($idNews);
        $em->remove($entities);
        $em->flush();
        // wywalic kierownika

        return $this->redirectToRoute('homepage');

    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/newsArchive", name = "newsArchive")
     */

    public function newsArchiveAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('PanelBundle:Aktualnosci')->findArchive(new \DateTime());
        return $this->render('PanelBundle:News:newsArchive.html.twig', array('entities' => $entities));
    }

}
