<?php

namespace PanelBundle\Controller;

use PanelBundle\Entity\Osoby;
use PanelBundle\Constants\Plec;
use PanelBundle\Constants\Sekcja;
use PanelBundle\Constants\Status;
use PanelBundle\Constants\Glos;
use PanelBundle\Model\ChangePassword;
use PanelBundle\PanelBundle;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Validator\Constraints\Date;
use Nzo\UrlEncryptorBundle\Annotations\ParamDecryptor;
use PanelBundle\Command\EmailSender;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class UsersController extends Controller
{
    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/userAdd", name = "userAdd")
     */
    public function userAddAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();

        $user = new Osoby();
        $tab = array('TAK' => 1, 'NIE' => 0);

        $form = $this->createFormBuilder($user)
            ->add('imie', TextType::class, array("label" => "Imię: ", 'attr' => array('class' => 'form-control col-md-7 col-xs-12')))
            ->add('nazwisko', TextType::class, array('label' => 'Nazwisko:', 'attr' => array('class' => 'form-control col-md-7 col-xs-12')))
            ->add('plec', ChoiceType::class, array('label' => 'Płeć:', 'expanded' => true, 'choices' => Plec::choiceTab()))
            ->add('powiadomienia', ChoiceType::class, array('label' => 'Powiadomienia mailowe o nowych postach, ankietach:', 'expanded' => true, 'choices' => $tab))
            ->add('dataUrodzenia', DateType::class, array('label' => 'Data urodzenia:', 'widget' => 'single_text', 'format' => 'dd/MM/yyyy', 'attr' => array('class' => 'form-control', 'data-inputmask' => "'mask': '99/99/9999'", 'placeholder' => 'dd/mm/rrrr')))
            ->add('miejsceUrodzenia', TextType::class, array('label' => 'Miejsce urodzenia:', 'attr' => array('class' => 'form-control col-md-7 col-xs-12')))
            ->add('telefon', TextType::class, array('label' => 'Telefon:', 'attr' => array('class' => 'form-control col-md-7 col-xs-12', 'data-inputmask' => "'mask': '999-999-999'")))
            ->add('mail', TextType::class, array('label' => 'Mail:', 'attr' => array('class' => 'form-control col-md-7 col-xs-12')))
            ->add('imieOjca', TextType::class, array('label' => 'Imię ojca:', 'attr' => array('class' => 'form-control col-md-7 col-xs-12')))
            ->add('imieMatki', TextType::class, array('label' => 'Imię matki:', 'attr' => array('class' => 'form-control col-md-7 col-xs-12')))
            ->add('nrDowodu', TextType::class, array('label' => 'Numer i seria dowodu:', 'attr' => array('class' => 'form-control col-md-7 col-xs-12')))
            ->add('pesel', TextType::class, array('label' => 'PESEL:', 'attr' => array('class' => 'form-control col-md-7 col-xs-12')))
            ->add('nrLegitymacji', TextType::class, array('label' => 'Numer legitymacji:', 'attr' => array('class' => 'form-control col-md-7 col-xs-12')))
            ->add('nrPaszportu', TextType::class, array('label' => 'Numer paszportu:', 'attr' => array('class' => 'form-control col-md-7 col-xs-12')))
            ->add('dataPaszport', DateType::class, array('label' => 'Data ważności paszportu:', 'widget' => 'single_text', 'format' => 'dd/MM/yyyy', 'attr' => array('class' => 'form-control', 'data-inputmask' => "'mask': '99/99/9999'", 'placeholder' => 'dd/mm/rrrr')))
            ->add('uczelnia', TextType::class, array('label' => 'Uczelnia:', 'attr' => array('class' => 'form-control col-md-7 col-xs-12', 'placeholder' => 'pełna nazwa')))
            ->add('wydzial', TextType::class, array('label' => 'Wydział:', 'attr' => array('class' => 'form-control col-md-7 col-xs-12', 'placeholder' => 'pełna nazwa')))
            ->add('rok', TextType::class, array('label' => 'Rok studiów:', 'attr' => array('class' => 'form-control col-md-7 col-xs-12', 'placeholder' => 'liczba')))
            ->add('adres', TextType::class, array('label' => 'Adres zameldowania:', 'attr' => array('class' => 'form-control col-md-7 col-xs-12')))
            ->add('wzrost', TextType::class, array('label' => 'Wzrost:', 'attr' => array('class' => 'form-control col-md-7 col-xs-12', 'placeholder' => 'cm')))
            ->add('sekcja', ChoiceType::class, array('label' => 'Sekcja:', 'expanded' => true, 'choices' => Sekcja::choiceTab()))
            ->add('status', ChoiceType::class, array('label' => 'Status:', 'expanded' => true, 'choices' => Status::choiceTab()))
            ->add('glos', ChoiceType::class, array('label' => 'Głos:', 'expanded' => true, 'choices' => Glos::choiceTab()))
            ->add('dataWstapienia', DateType::class, array('label' => 'Data wstąpienia:', 'widget' => 'single_text', 'format' => 'dd/MM/yyyy', 'attr' => array('class' => 'form-control', 'data-inputmask' => "'mask': '99/99/9999'", 'placeholder' => 'dd/mm/rrrr')))
            ->add('dataWystapienia', DateType::class, array('label' => 'Data wystąpienia:', 'required' => false, 'widget' => 'single_text', 'format' => 'dd/MM/yyyy', 'attr' => array('class' => 'form-control', 'data-inputmask' => "'mask': '99/99/9999'", 'placeholder' => 'dd/mm/rrrr')))
            ->add('opis', TextareaType::class, array('label' => 'O sobie:', 'attr' => array('class' => 'form-control col-md-7 col-xs-12')))
            ->add('save', SubmitType::class, array('label' => 'Zapisz', 'attr' => array('class' => 'btn btn-success')))
            ->getForm();


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $user->setLogin($em->getRepository('PanelBundle:Osoby')->createLogin($user->getImie(), $user->getNazwisko()));
            $user->setHaslo(\PanelBundle\Model\ChangePassword::generujHaslo());
            $user->setRole(array('ROLE_USER'));
            $wiadomosc = 'Witaj, ' . $user->__toString() . '<br><br>Utworzono nowe konto w naszym systemie:<br><br>Login: <b>' . $user->getLogin()
                . '</b><br>Hasło: <b>' . $user->getHaslo() . '</b><br><br>Link do systemu: ' . $this->generateUrl('homepage', array(/* 'uuid' => $user->getUuid() */), UrlGeneratorInterface::ABSOLUTE_URL) . '<br>';
            $mail = new EmailSender();
            $mail->CreateEmail($user->getMail(), 'Utworzono konto', $wiadomosc);
            $mail->SendEmail();
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('users');
        }

        return $this->render('PanelBundle:Users:userEdit.html.twig', array('form' => $form->createView(), 'title' => 'Dodawanie'));
    }

    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/userEdit/{idOsoba}", name = "userEdit")
     * @ParamDecryptor(params={"idOsoba"})
     */
    public function userEditAction(Request $request, $idOsoba = false)
    {
        if ($idOsoba == $this->get('nzo_url_encryptor')->decrypt(false)) {
            $idOsoba = false;
        }
        $em = $this->getDoctrine()->getManager();

        if ($idOsoba) {
            $user = $em->getRepository('PanelBundle:Osoby')->find($idOsoba);
        } else {
            $user = $this->getUser();
        }
        $tab = array('TAK' => 1, 'NIE' => 0);


        $form = $this->createFormBuilder($user)
            ->add('imie', TextType::class, array("label" => "Imię: ", 'attr' => array('class' => 'form-control col-md-7 col-xs-12')))
            ->add('nazwisko', TextType::class, array('label' => 'Nazwisko:', 'attr' => array('class' => 'form-control col-md-7 col-xs-12')))
            ->add('plec', ChoiceType::class, array('label' => 'Płeć:', 'expanded' => true, 'choices' => Plec::choiceTab()))
            ->add('powiadomienia', ChoiceType::class, array('label' => 'Powiadomienia mailowe o nowych postach, ankietach:', 'expanded' => true, 'choices' => $tab))
            ->add('dataUrodzenia', DateType::class, array('label' => 'Data urodzenia:', 'widget' => 'single_text', 'format' => 'dd/MM/yyyy', 'attr' => array('class' => 'form-control', 'data-inputmask' => "'mask': '99/99/9999'", 'placeholder' => 'dd/mm/rrrr')))
            ->add('miejsceUrodzenia', TextType::class, array('label' => 'Miejsce urodzenia:', 'attr' => array('class' => 'form-control col-md-7 col-xs-12')))
            ->add('telefon', TextType::class, array('label' => 'Telefon:', 'attr' => array('class' => 'form-control col-md-7 col-xs-12', 'data-inputmask' => "'mask': '999-999-999'")))
            ->add('mail', TextType::class, array('label' => 'Mail:', 'attr' => array('class' => 'form-control col-md-7 col-xs-12')))
            ->add('imieOjca', TextType::class, array('label' => 'Imię ojca:', 'attr' => array('class' => 'form-control col-md-7 col-xs-12')))
            ->add('imieMatki', TextType::class, array('label' => 'Imię matki:', 'attr' => array('class' => 'form-control col-md-7 col-xs-12')))
            ->add('nrDowodu', TextType::class, array('label' => 'Numer i seria dowodu:', 'attr' => array('class' => 'form-control col-md-7 col-xs-12')))
            ->add('pesel', TextType::class, array('label' => 'PESEL:', 'attr' => array('class' => 'form-control col-md-7 col-xs-12')))
            ->add('nrLegitymacji', TextType::class, array('label' => 'Numer legitymacji:', 'attr' => array('class' => 'form-control col-md-7 col-xs-12')))
            ->add('nrPaszportu', TextType::class, array('label' => 'Numer paszportu:', 'attr' => array('class' => 'form-control col-md-7 col-xs-12')))
            ->add('dataPaszport', DateType::class, array('label' => 'Data ważności paszportu:', 'data' => $user->getDataPaszport() > new \DateTime('1970-01-01 00:00:00') ? $user->getDataPaszport() : NULL, 'widget' => 'single_text', 'format' => 'dd/MM/yyyy', 'attr' => array('class' => 'form-control', 'data-inputmask' => "'mask': '99/99/9999'", 'placeholder' => 'dd/mm/rrrr')))
            ->add('uczelnia', TextType::class, array('label' => 'Uczelnia:', 'attr' => array('class' => 'form-control col-md-7 col-xs-12', 'placeholder' => 'pełna nazwa')))
            ->add('wydzial', TextType::class, array('label' => 'Wydział:', 'attr' => array('class' => 'form-control col-md-7 col-xs-12', 'placeholder' => 'pełna nazwa')))
            ->add('rok', TextType::class, array('label' => 'Rok studiów:', 'attr' => array('class' => 'form-control col-md-7 col-xs-12', 'placeholder' => 'liczba')))
            ->add('adres', TextType::class, array('label' => 'Adres zameldowania:', 'attr' => array('class' => 'form-control col-md-7 col-xs-12')))
            ->add('wzrost', TextType::class, array('label' => 'Wzrost:', 'attr' => array('class' => 'form-control col-md-7 col-xs-12', 'placeholder' => 'cm')))
            ->add('sekcja', ChoiceType::class, array('label' => 'Sekcja:', 'expanded' => true, 'choices' => Sekcja::choiceTab()))
            ->add('status', ChoiceType::class, array('label' => 'Status:', 'expanded' => true, 'choices' => Status::choiceTab()))
            ->add('glos', ChoiceType::class, array('label' => 'Głos:', 'expanded' => true, 'choices' => Glos::choiceTab()))
            ->add('dataWstapienia', DateType::class, array('label' => 'Data wstąpienia:', 'widget' => 'single_text', 'format' => 'dd/MM/yyyy', 'attr' => array('class' => 'form-control', 'data-inputmask' => "'mask': '99/99/9999'", 'placeholder' => 'dd/mm/rrrr')))
            ->add('dataWystapienia', DateType::class, array('label' => 'Data wystąpienia:', 'data' => $user->getDataWystapienia() > new \DateTime('1970-01-01 00:00:00') ? $user->getDataWystapienia() : NULL, 'required' => false, 'widget' => 'single_text', 'format' => 'dd/MM/yyyy', 'attr' => array('class' => 'form-control', 'data-inputmask' => "'mask': '99/99/9999'", 'placeholder' => 'dd/mm/rrrr')))
            ->add('opis', TextareaType::class, array('label' => 'O sobie:', 'attr' => array('class' => 'form-control col-md-7 col-xs-12')))
            ->add('save', SubmitType::class, array('label' => 'Zapisz', 'attr' => array('class' => 'btn btn-success')))
            ->getForm();


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($user);
            $em->flush();
            $userId = $this->get('nzo_url_encryptor')->encrypt($user->getId());
            return $idOsoba ? $this->redirectToRoute('profile', array('idUser' => $userId)) : $this->redirectToRoute('profile');
        }

        return $this->render('PanelBundle:Users:userEdit.html.twig', array('form' => $form->createView(), 'title' => 'Edycja'));
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/users", name = "users")
     */
    public function usersAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('PanelBundle:Osoby')->findBy(array('status' => 2));
        return $this->render('PanelBundle:Users:users.html.twig', array('entities' => $entities));
    }

    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/profile/{idUser}", name = "profile")
     * @ParamDecryptor(params={"idUser"})
     */
    public function profileAction(Request $request, $idUser = false)
    {
        if ($idUser == $this->get('nzo_url_encryptor')->decrypt(false)) {
            $idUser = false;
        }
        $em = $this->getDoctrine()->getManager();
        if ($idUser) {
            $user = $em->getRepository('PanelBundle:Osoby')->find($idUser);
        } else {
            $user = $this->getUser();
        }

        $form = $this->createFormBuilder()
            ->add('zdjecie', FileType::class, array('label' => 'Zdjęcie', 'required' => false, 'data_class' => UploadedFile::class))
            ->add('save', SubmitType::class, array('label' => 'Zapisz', 'attr' => array('class' => 'btn btn-success')))
            ->getForm();

        $form->handleRequest($request);
        if ($form->isValid() && $form->isSubmitted()) {
            $data = $form->getData();
            $file = $data['zdjecie'];
            $currdate = new \DateTime('now');
            $filename = $user->getLogin() .'_'. $currdate->format('dmY') . '.' . $file->guessExtension();
            $file->move($this->getParameter('uploads_directory'), $filename);
            $user->setZdjecie('/zdjecia_osob/'.$filename);
            $em->persist($user);
            $em->flush();
        }
        return $this->render('PanelBundle:Users:profile.html.twig', array('user' => $user, 'form' => $form->createView()));
    }

    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/changePassword", name = "changePassword")
     */
    public function changePasswordAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $data['haslo'] = $this->getUser()->getHaslo();
        $zmianaHasla = new ChangePassword();
        $form = $this->createFormBuilder($zmianaHasla)
            ->add('stareHaslo', PasswordType::class, array("label" => "Podaj aktualne hasło:", 'attr' => array('class' => 'form-control col-md-7 col-xs-12')))
            ->add('noweHaslo', PasswordType::class, array('label' => 'Podaj nowe hasło:', 'attr' => array('class' => 'form-control col-md-7 col-xs-12')))
            ->add('powtorzHaslo', PasswordType::class, array('label' => 'Powtórz nowe hasło:', 'attr' => array('class' => 'form-control col-md-7 col-xs-12')))
            ->add('save', SubmitType::class, array('label' => 'Zmień', 'attr' => array('class' => 'btn btn-success')))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getDoctrine()->getRepository('PanelBundle:Osoby')->find($this->getUser()->getId());
            $user->setHaslo($zmianaHasla->getHaslo());
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('logout');
        }

        return $this->render("PanelBundle:Users:changePassword.html.twig", array('form' => $form->createView(), 'show' => FALSE));
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/resetHasla/{id}", name = "resetHasla")
     * @ParamDecryptor(params={"id"})
     */
    public function ResetHaslaAction($id)
    {
        $wiadomosc = 'Zresetowano hasło w panelu ZPiT AGH Krakus. Nowe dane do logowania:<br><br>';
        $em = $this->getDoctrine()->getManager();
        $user = $this->getDoctrine()->getRepository('PanelBundle:Osoby')->find($id);
        $login = $user->getLogin();
        $haslo = \PanelBundle\Model\ChangePassword::generujHaslo();
        $wiadomosc .= 'Witaj, ' . $user->__toString() . '<br><br>Login: <b>' . $login . '</b><br>Hasło: <b>' . $haslo . '</b><br><br>Link do systemu: ' . $this->generateUrl('homepage', array(/* 'uuid' => $user->getUuid() */), UrlGeneratorInterface::ABSOLUTE_URL) . '<br>';
        $mail = new EmailSender();
        $mail->CreateEmail($user->getMail(), 'Zresetowano hasło', $wiadomosc);
        $mail->SendEmail();
        //$user->setHaslo(\OcenyBundle\Model\ChangePassword::szyfrujHaslo($haslo));
        $user->setHaslo($haslo);
        $em->flush();
        return $this->redirectToRoute('index');
    }
}
