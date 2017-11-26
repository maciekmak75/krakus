<?php

namespace PanelBundle\Constants;

class Sekcja extends BasicEnum
{

    const Nieokreslony = 0;
    const Balet = 1;
    const Chor = 2;
    const Kapela = 4;

    static public function choiceTab()
    {
        $names = [];

        $names['Balet'] = Sekcja::Balet;
        $names['Chór'] = Sekcja::Chor;
        $names['Kapela'] = Sekcja::Kapela;


        return $names;
    }

    public function sekcjaNazwa($it)
    {
        $expr = '';
        if ($it == Sekcja::Nieokreslony) $expr = 'Nieokreślony';
        if ($it == Sekcja::Balet) $expr = 'Balet';
        if ($it == Sekcja::Chor) $expr = 'Chór';
        if ($it == Sekcja::Kapela) $expr = 'Kapela';

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
        if ($st == Sekcja::Balet) {
            if ($it == 1 || $it == 3 || $it == 5 || $it == 7)
                return true;
        }
        if ($st == Sekcja::Chor) {
            if ($it == 2 || $it == 3 || $it == 6 || $it == 7)
                return true;
        }
        if ($st == Sekcja::Kapela) {
            if ($it == 4 || $it == 5 || $it == 6 || $it == 7)
                return true;
        }

        return false;

    }

    public static function ktoraSekcja($it)
    {
        if ($it == 1) $expr = 'Balet';
        if ($it == 2) $expr = 'Chór';
        if ($it == 3) $expr = 'Chór + Balet';
        if ($it == 4) $expr = 'Kapela';
        if ($it == 5) $expr = 'Kapela + Balet';
        if ($it == 6) $expr = 'Kapela + Chór';
        if ($it == 7) $expr = 'Kapela + Chór + Balet';
        return $expr;

    }

    public static function getSekcja($it)
    {
        if ($it == 1) $expr[0] = Sekcja::Balet;
        if ($it == 2) $expr[0] = Sekcja::Chor;
        if ($it == 3) $expr = array(0 => Sekcja::Balet, 1 => Sekcja::Chor);
        if ($it == 4) $expr[0] = Sekcja::Kapela;
        if ($it == 5) $expr = array(0 => Sekcja::Balet, 1 => Sekcja::Kapela);
        if ($it == 6) $expr = array(0 => Sekcja::Chor, 1 => Sekcja::Kapela);
        if ($it == 7) $expr = array(0 => Sekcja::Balet, 1 => Sekcja::Chor, 2 => Sekcja::Kapela);
        return $expr;

    }
}