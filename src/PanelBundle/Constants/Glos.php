<?php

namespace PanelBundle\Constants;

class Glos extends BasicEnum {

    const Nieokreslony = 0;
    const Sopran = 1;
    const Alt = 2;
    const Tenor = 3;
    const Bas = 4;

    static public function choiceTab()
    {
        $names = [];

        $names['Nie dotyczy'] = Glos::Nieokreslony;
        $names['Sopran'] = Glos::Sopran;
        $names['Alt'] = Glos::Alt;
        $names['Tenor'] = Glos::Tenor;
        $names['Bas'] = Glos::Bas;


        return $names;
    }

    public function glosNazwa($it)
    {
        $exp = 'Nie dotyczy';
        if($it == 1) $exp = 'Sopran';
        if($it == 2) $exp = 'Alt';
        if($it == 3) $exp = 'Tenor';
        if($it == 4) $exp = 'Bas';
        return $exp;
    }
}