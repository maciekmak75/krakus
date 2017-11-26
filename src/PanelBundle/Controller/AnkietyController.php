<?php

namespace PanelBundle\Controller;

use PanelBundle\Entity\Ankieta;
use PanelBundle\Entity\MozliweOdpowiedzi;
use PanelBundle\Entity\Odpowiedzi;
use Nzo\UrlEncryptorBundle\Annotations\ParamDecryptor;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
//use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Tests\Model;
use PanelBundle\Command\EmailSender;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class AnkietyController extends Controller
{
    /**
     *
     * @Route("/ankietaEdit/{idAnkieta}", name = "ankietaEdit")
     * @Security("has_role('ROLE_ADMIN')")
     * @ParamDecryptor(params={"idAnkieta"})
     */
    public function ankietaEditAction(Request $request, $idAnkieta)
    {
        $em = $this->getDoctrine()->getManager();

        $ankieta = $em->getRepository('PanelBundle:Ankieta')->find($idAnkieta);
        $odp = $em->getRepository('PanelBundle:MozliweOdpowiedzi')->findBy(array('idAnkieta' => $idAnkieta));
        $title = 'Edycja';
        $ilosc = count($odp);
        $show = array('TAK' => 1, 'NIE' => 0);
        $typ = array('Jednokrotny' => 1, 'Wielokrotny' => 0);

        $form = $this->createFormBuilder()
            ->add('pytanie', TextType::class, array('label' => 'Pytanie:', 'required' => true, 'attr' => array('class' => 'form-control col-md-7 col-xs-12'), 'data' => $ankieta->getPytanie()))
            ->add('odp1', TextType::class, array('label' => 'Odpowiedź 1:', 'required' => true, 'data' => $ilosc > 0 ? $odp[0]->getNazwa() : '', 'attr' => array('class' => 'form-control col-md-7 col-xs-12')))
            ->add('odp2', TextType::class, array('label' => 'Odpowiedź 2:', 'required' => true, 'data' => $ilosc > 1 ? $odp[1]->getNazwa() : '', 'attr' => array('class' => 'form-control col-md-7 col-xs-12')))
            ->add('odp3', TextType::class, array('label' => 'Odpowiedź 3:', 'data' => $ilosc > 2 ? $odp[2]->getNazwa() : '', 'attr' => array('class' => 'form-control col-md-7 col-xs-12')))
            ->add('odp4', TextType::class, array('label' => 'Odpowiedź 4:', 'data' => $ilosc > 3 ? $odp[3]->getNazwa() : '', 'attr' => array('class' => 'form-control col-md-7 col-xs-12')))
            ->add('odp5', TextType::class, array('label' => 'Odpowiedź 5:', 'data' => $ilosc > 4 ? $odp[4]->getNazwa() : '', 'attr' => array('class' => 'form-control col-md-7 col-xs-12')))
            ->add('czyPokazywac', ChoiceType::class, array('label' => 'Pokazywać:', 'choices' => $show, 'data' => $ankieta->getCzyPokazywac(), 'expanded' => true, 'required' => true))
            ->add('typ', ChoiceType::class, array('label' => 'Wybór odpowiedzi:', 'choices' => $typ, 'data' => $ankieta->getTyp(), 'expanded' => true, 'required' => true))
            ->add('dlaKogoStatus', ChoiceType::class, array('label' => 'Dla kogo:', 'choices' => $this->get('panel.status')->choiceTab(), 'data' => $this->get('panel.status')->getStatus($ankieta->getStatus()), 'expanded' => true, 'multiple' => true, 'required' => true))
            ->add('dlaKogoSekcja', ChoiceType::class, array('label' => 'Sekcja:', 'choices' => $this->get('panel.sekcja')->choiceTab(), 'data' => $this->get('panel.sekcja')->getSekcja($ankieta->getSekcja()), 'expanded' => true, 'multiple' => true, 'required' => true))
            ->add('save', SubmitType::class, array('label' => 'Zapisz', 'attr' => array('class' => 'btn btn-success')))
            ->getForm();

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $ankieta->setPytanie($data['pytanie']);
            $ankieta->setCzyPokazywac($data['czyPokazywac']);
            $ankieta->setTyp($data['typ']);
            $ankieta->setStatus($this->get('panel.status')->setUprawnienia($data['dlaKogoStatus']));
            $ankieta->setSekcja($this->get('panel.sekcja')->setUprawnienia($data['dlaKogoSekcja']));
            $em->persist($ankieta);
            $em->flush();
            for ($i = 0; $i < $ilosc; $i++) {
                $em->remove($odp[$i]);
                $em->flush();
            }
            for ($i = 1; $i < 6; $i++) {

                if ($data['odp' . $i] != '') {
                    $odpx = new MozliweOdpowiedzi();
                    $odpx->setIdAnkieta($em->getReference('PanelBundle:Ankieta', $ankieta->getId()));
                    $odpx->setNazwa($data['odp' . $i]);
                    $em->persist($odpx);
                }
            }

            $em->flush();
            $mail = new EmailSender();
            $wiadomosc = 'Witaj, <br><br> W panelu pojawiła się nowa ankieta do wypełnienia. Zaloguj się do panelu<br><br>'.
                 $this->generateUrl('ankietyRender', array(/* 'uuid' => $user->getUuid() */), UrlGeneratorInterface::ABSOLUTE_URL);
            $adresy = $em->getRepository('PanelBundle:Osoby')->findPersonToNotificate($ankieta->getStatus(), $ankieta->getSekcja());
            echo '<pre>';
            \Doctrine\Common\Util\Debug::dump($adresy);
            echo '</pre>';
            $mail->CreateEmail('powiadomienia@krakus.net', 'Nowa ankieta', $wiadomosc, $adresy);
            $mail->SendEmail();
            return $this->redirectToRoute('ankiety');

        }
        return $this->render("PanelBundle:Surveys:surveysEdit.html.twig", array('form' => $form->createView(), 'title' => $title));
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/ankiety", name = "ankiety")
     */
    public function ankietyAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('PanelBundle:Ankieta')->findBy(array(), array('id' => 'DESC'));


        $show = array('TAK' => 1, 'NIE' => 0);
        $typ = array('Jednokrotny' => 1, 'Wielokrotny' => 0);
        $form = $this->createFormBuilder()
            ->add('pytanie', TextType::class, array('label' => 'Pytanie:', 'required' => true, 'attr' => array('class' => 'form-control col-md-7 col-xs-12')))
            ->add('odp1', TextType::class, array('label' => 'Odpowiedź 1:', 'required' => true, 'attr' => array('class' => 'form-control col-md-7 col-xs-12')))
            ->add('odp2', TextType::class, array('label' => 'Odpowiedź 2:', 'required' => true, 'attr' => array('class' => 'form-control col-md-7 col-xs-12')))
            ->add('odp3', TextType::class, array('label' => 'Odpowiedź 3:', 'attr' => array('class' => 'form-control col-md-7 col-xs-12')))
            ->add('odp4', TextType::class, array('label' => 'Odpowiedź 4:', 'attr' => array('class' => 'form-control col-md-7 col-xs-12')))
            ->add('odp5', TextType::class, array('label' => 'Odpowiedź 5:', 'attr' => array('class' => 'form-control col-md-7 col-xs-12')))
            ->add('czyPokazywac', ChoiceType::class, array('label' => 'Pokazywać:', 'choices' => $show, 'expanded' => true, 'required' => true))
            ->add('typ', ChoiceType::class, array('label' => 'Wybór odpowiedzi:', 'choices' => $typ, 'expanded' => true, 'required' => true))
            ->add('dlaKogoStatus', ChoiceType::class, array('label' => 'Dla kogo:', 'choices' => $this->get('panel.status')->choiceTab(), 'expanded' => true, 'multiple' => true, 'required' => true))
            ->add('dlaKogoSekcja', ChoiceType::class, array('label' => 'Sekcja:', 'choices' => $this->get('panel.sekcja')->choiceTab(), 'expanded' => true, 'multiple' => true, 'required' => true))
            ->add('save', SubmitType::class, array('label' => 'Zapisz', 'attr' => array('class' => 'btn btn-success')))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $ankieta = new Ankieta();
            $ankieta->setPytanie($data['pytanie']);
            $ankieta->setCzyPokazywac($data['czyPokazywac']);
            $ankieta->setTyp($data['typ']);
            $ankieta->setStatus($this->get('panel.status')->setUprawnienia($data['dlaKogoStatus']));
            $ankieta->setSekcja($this->get('panel.sekcja')->setUprawnienia($data['dlaKogoSekcja']));
            $em->persist($ankieta);
            $em->flush();
            for ($i = 1; $i < 6; $i++) {
                if ($data['odp' . $i] != '') {
                    $odp = new MozliweOdpowiedzi();
                    $odp->setIdAnkieta($em->getReference('PanelBundle:Ankieta', $ankieta->getId()));
                    $odp->setNazwa($data['odp' . $i]);
                    $em->persist($odp);
                }
            }

            $em->flush();
            $mail = new EmailSender();
            $wiadomosc = 'Witaj, <br><br> W panelu pojawiła się nowa ankieta do wypełnienia. Zaloguj się do panelu<br><br>'.
                $this->generateUrl('ankietyRender', array(/* 'uuid' => $user->getUuid() */), UrlGeneratorInterface::ABSOLUTE_URL);
            $adresy = $em->getRepository('PanelBundle:Osoby')->findPersonToNotificate($ankieta->getStatus(), $ankieta->getSekcja());
            $mail->CreateEmail('powiadomienia@krakus.net', 'Nowa ankieta', $wiadomosc, $adresy);
            $mail->SendEmail();
            return $this->redirectToRoute('ankiety');

        }
        return $this->render('PanelBundle:Surveys:surveys.html.twig', array('form' => $form->createView(), 'entities' => $entities));
    }

    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/ankietyRender", name = "ankietyRender")
     */
    public function ankietyRenderAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $entities = [];
        $answers = [];
        $k = 0;
        $entity = $em->getRepository('PanelBundle:Ankieta')->findBy(array('czyPokazywac' => true));
        for ($i = 0; $i < count($entity); $i++) {
            $answ = $em->getRepository('PanelBundle:Odpowiedzi')->findOneBy(array('idAnkieta' => $entity[$i]->getId(), 'idUzytkownik' => $this->getUser()));
            $war1 = $this->get('panel.status')->czyPokazywac($this->getUser()->getStatus(), $entity[$i]->getStatus());
            $war2 = $this->get('panel.sekcja')->czyPokazywac($this->getUser()->getSekcja(), $entity[$i]->getSekcja());
            if (!$answ && $war1 && $war2) {
                $entities[$k] = $entity[$i];
                $answers[$k] = $em->getRepository('PanelBundle:MozliweOdpowiedzi')->findSelectAnswers($entities[$k]->getId());
                $k++;
            }
        }
        if ($entities) {

            $form = $this->createFormBuilder();
            for ($i = 0; $i < count($entities); $i++) {
                $form->add($i, ChoiceType::class, array('label' => $entities[$i]->getPytanie(), 'required' => true, 'multiple' => !$entities[$i]->getTyp(), 'expanded' => true, 'choices' => $answers[$i]));
            }
            $form->add('save', SubmitType::class, array('label' => 'Zapisz', 'attr' => array('class' => 'btn btn-success')));
            $render = $form->getForm();
            $render->handleRequest($request);
            if ($render->isSubmitted() && $render->isValid()) {
                $data = $render->getData();
                for ($i = 0; $i < count($entities); $i++) {
                    $odp = new Odpowiedzi();
                    $odp->setIdAnkieta($em->getReference('PanelBundle:Ankieta', $entities[$i]->getId()));
                    $odp->setIdUzytkownik($em->getReference('PanelBundle:Osoby', $this->getUser()->getId()));
                    $odp->setIdOdpowiedz($em->getReference('PanelBundle:MozliweOdpowiedzi', $data[$i]));
                    $em->persist($odp);
                }
                $em->flush();
                return $this->redirectToRoute('index');
            }
            return $this->render('PanelBundle:Surveys:surveysRender.html.twig', array('form' => $render->createView()));
        } else {
            return $this->redirectToRoute('index');
        }

    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/ankietyEnd/{idAnkieta}", name = "ankietyEnd")
     * @ParamDecryptor(params={"idAnkieta"})
     */
    public function ankietyEndAction($idAnkieta)
    {
        $em = $this->getDoctrine()->getManager();
        $surv = $em->getRepository('PanelBundle:Ankieta')->find($idAnkieta);
        $surv->setCzyPokazywac(false);
        $em->persist($surv);
        $em->flush();
        return $this->redirectToRoute('ankiety');
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/ankietyWyniki/{idAnkieta}", name = "ankietyWyniki")
     * @ParamDecryptor(params={"idAnkieta"})
     */
    public function ankietyWynikiAction($idAnkieta)
    {
        $em = $this->getDoctrine()->getManager();
        $ankieta = $em->getRepository('PanelBundle:Ankieta')->find($idAnkieta);
        $answers = $em->getRepository('PanelBundle:MozliweOdpowiedzi')->findBy(array('idAnkieta' => $idAnkieta));



        for ($i = 0;$i < count($answers); $i++)
        {
            $entities[$i] = $em->getRepository('PanelBundle:Odpowiedzi')->findBy(array('idAnkieta'=>$idAnkieta, 'idOdpowiedz'=>$answers[$i]->getId()));
        }
        if ($ankieta->getStatus() < 4) {
            $users = $em->getRepository('PanelBundle:Osoby')->findNoAnswer($idAnkieta, $ankieta->getSekcja(), $ankieta->getStatus());
            $odp = new MozliweOdpowiedzi();

            $odp->setNazwa('Nie odpowiedziało');
            $answers[count($answers)] = $odp;
            $uz = [];
            for($i = 0;$i<count($users);$i++){
                $uz[$i]  = new Odpowiedzi();
                $uz[$i]->setIdUzytkownik($em->getReference('PanelBundle:Osoby',$users[$i]->getId()));
            }
            $entities[count($entities)] = $uz;

        }



        return $this->render('PanelBundle:Surveys:surveysAnswers.html.twig', array('answers' => $answers, 'entities' => $entities, 'survey'=>$ankieta));
    }
}
