<?php

namespace PanelBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Nzo\UrlEncryptorBundle\Annotations\ParamDecryptor;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\HttpFoundation\JsonResponse;

class AddressBookController extends Controller
{
    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/ksiazkaAdresowa/{mode}", name = "ksiazkaAdresowa")
     * @ParamDecryptor(params={"mode"})
     */
    public function ksiazkaAdresowaAction($mode)
    {
        $em = $this->getDoctrine()->getManager();
        if ($mode == 1) {
            /*echo '<pre>';
            \Doctrine\Common\Util\Debug::dump($this->AddressBookAjaxAction('Maksoń'));
            echo '</pre>';*/
            $html = $this->AddressBookAjaxAction('Maciej');
            $entities = $em->getRepository('PanelBundle:Osoby')->findBy(array(
                'status' => $this->get('panel.status')->choiceTab()['Członek'],
                'sekcja' => $this->get('panel.sekcja')->choiceTab()['Balet']),
                array('nazwisko' => 'ASC'));
            $title = 'Balet';
        }
        if ($mode == 2) {
            $entities = $em->getRepository('PanelBundle:Osoby')->findBy(array(
                'status' => $this->get('panel.status')->choiceTab()['Członek'],
                'sekcja' => $this->get('panel.sekcja')->choiceTab()['Chór']),
                array('nazwisko' => 'ASC'));
            $title = 'Chór';

        }
        if ($mode == 3) {
            $entities = $em->getRepository('PanelBundle:Osoby')->findBy(array(
                'status' => $this->get('panel.status')->choiceTab()['Członek'],
                'sekcja' => $this->get('panel.sekcja')->choiceTab()['Kapela']),
                array('nazwisko' => 'ASC'));
            $title = 'Kapela';

        }
        if ($mode == 4) {
            $entities = $em->getRepository('PanelBundle:Osoby')->findBy(array(
                'status' => $this->get('panel.status')->choiceTab()['Nabór']),
                array('nazwisko' => 'ASC'));
            $title = 'Nabór';

        }
        if ($mode == 5) {
            $entities = $em->getRepository('PanelBundle:Osoby')->findBy(array(
                'status' => $this->get('panel.status')->choiceTab()['Wychowanek']),
                array('nazwisko' => 'ASC'));
            $title = 'Wychowankowie';

        }


        return $this->render('PanelBundle:AddressBook:addressBook.html.twig', array('entities' => $entities, 'title' => $title));
    }


    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/ksiazkaAdresowaAjax", name = "ksiazkaAdresowaAjax")
     * @Method({"POST"})
     */
    public function AddressBookAjaxAction()
    {
        $request = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $persons = $em->getRepository('PanelBundle:Osoby')->findPerson($request->request->get('firstLastName'));
        $i = 0;
        $personInfoHtml = '                
                 <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_content">
                            <div class="row" >
                                <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                                    
                                </div>

                                <div class="clearfix"></div>';
        while ($i < count($persons)) {
            $personInfoHtml .= '<div class="col-md-4 col-sm-4 col-xs-12 profile_details ">
                          <div class="well profile_view col-md-12 col-sm-12 col-xs-12 ">
                            <div class="col-sm-12">
                              <h4 class="brief"><i>' . $this->get('panel.sekcja')->sekcjaNazwa($persons[$i]->getSekcja()) . ', ' . $this->get('panel.status')->statusNazwa($persons[$i]->getStatus()) . '</i></h4>
                              <div class="left col-xs-7">
                                <h2>' . $persons[$i]->__toString() . '</h2>
                                <ul class="list-unstyled">
                                  <li><i class="fa fa-building"></i> E-mail: ' . $persons[$i]->getMail() . '</li>
                                  <li><i class="fa fa-phone"></i> Telefon: ' . $persons[$i]->getTelefon() . '</li>
                                  <li><i class="fa fa-plus"></i> Data wstąpienia: ' /*. $persons[$i]->getDataWstapienia()!=NULL?$persons[$i]->getDataWstapienia()->format('Y-m-d'):'' */. '</li>
                                  <li><i class="fa fa-graduation-cap"></i> Data wystąpienia: ' . /*($persons[$i]->getDataWystapienia() == NULL)?'' :$persons[$i]->getDataWystapienia()->format('Y-m-d').*/'</li>
                                </ul>
                              </div>
                              <div class="right col-xs-5 text-center">
                                <img src="/panel/assets' . ($persons[$i]->getZdjecie() ? $persons[$i]->getZdjecie() : ($persons[$i]->getPlec() ? "/zdjecia_osob/user.png" : "/zdjecia_osob/default_female.jpg")) .
                '" alt="" class="img-thumbnail img-responsive">
                              </div>
                            </div>
                            <div class="col-xs-12 bottom text-center">
                <div class="col-md-12 col-sm-12 col-xs-12 emphasis">'
                . $persons[$i]->getOpis() . '
                                </div>
                            </div>
                          </div>
                        </div>';
            $i++;
        }
        $personInfoHtml .= '
                        </div>
                    </div>
                </div>
            </div>';
        $response = new JsonResponse();
        $response->setData(array('personInfoHtml' => $personInfoHtml));
        return $response;
    }
}
