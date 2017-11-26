<?php

namespace PanelBundle\Constants;

class Plec extends BasicEnum {

    const Kobieta = 0;
    const Mezczyzna = 1;


    static public function choiceTab()
    {
        $names = [];

        $names['Kobieta'] = Plec::Kobieta;
        $names['Mężczyzna'] = Plec::Mezczyzna;

        return $names;
    }

    public function plecNazwa($it)
    {
        $it? $exp= 'Mężczyzna' : $exp = 'Kobieta';
        return $exp;
    }
}