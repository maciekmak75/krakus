<?php

namespace PanelBundle\Constants;

class Status extends BasicEnum
{


    const Nabor = 1;
    const Czlonek = 2;
    const Wychowanek = 4;


    static public function choiceTab()
    {
        $names = [];

        $names['Nabór'] = Status::Nabor;
        $names['Członek'] = Status::Czlonek;
        $names['Wychowanek'] = Status::Wychowanek;


        return $names;
    }

    static public function choiceNewsTab()
    {
        $names = [];

        $names['Nabór'] = Status::Nabor;
        $names['Członek'] = Status::Czlonek;
        $names['Wychowanek'] = Status::Wychowanek;


        return $names;
    }

    public function statusNazwa($it)
    {
        $expr = 'Niekoreślony';
        //if ($it == Status::Nieokreslony) $expr = 'Nieokreślony';
        if ($it == Status::Nabor) $expr = 'Nabór';
        if ($it == Status::Czlonek) $expr = 'Członek';
        if ($it == Status::Wychowanek) $expr = 'Wychowanek';

        return $expr;
    }

    public static function setUprawnienia($tab)
    {
        $suma = 0;
        foreach ($tab as $key => $value){
            $suma +=$value;
        }
        /*for ($i = 0; $i < count($tab); $i++) {
            $suma += $tab[$i];
        }*/
        return $suma;
    }

    public static function czyPokazywac($st, $it)
    {
        if ($st == Status::Nabor) {
            if ($it == 1 || $it == 3 || $it == 5 || $it == 7)
                return true;
        }
        if ($st == Status::Czlonek) {
            if ($it == 2 || $it == 3 || $it == 6 || $it == 7)
                return true;
        }
        if ($st == Status::Wychowanek) {
            if ($it == 4 || $it == 5 || $it == 6 || $it == 7)
                return true;
        }

        return false;

    }

    public static function ktoraSekcja($it)
    {
        if ($it == 1) $expr = 'Nabór';
        if ($it == 2) $expr = 'Członek';
        if ($it == 3) $expr = 'Członek + Nabór';
        if ($it == 4) $expr = 'Wychowanek';
        if ($it == 5) $expr = 'Wychowanek + Nabór';
        if ($it == 6) $expr = 'Wychowanek + Członek';
        if ($it == 7) $expr = 'Wychowanek + Członek + Nabór';
        return $expr;

    }

    public static function getStatus($it)
    {
        if ($it == 1) $expr[0] = Status::Nabor;
        if ($it == 2) $expr[0] = Status::Czlonek;
        if ($it == 3) $expr = array(0 => Status::Nabor, 1 => Status::Czlonek);
        if ($it == 4) $expr[0] = Status::Wychowanek;
        if ($it == 5) $expr = array(0 => Status::Nabor, 1 => Status::Wychowanek);
        if ($it == 6) $expr = array(0 => Status::Czlonek, 1 => Status::Wychowanek);
        if ($it == 7) $expr = array(0 => Status::Nabor, 1 => Status::Czlonek, 2 => Status::Wychowanek);
        return $expr;

    }
}