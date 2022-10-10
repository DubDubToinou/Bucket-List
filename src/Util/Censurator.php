<?php

namespace App\Util;


class Censurator
{

    const MOT_INTERDIT=['Carotte', 'Argent'];

    public function purify( string $text): string{

        foreach (self::MOT_INTERDIT as $mot_interdit){
            $remplacement = str_repeat("*", mb_strlen($mot_interdit));
            $text = str_ireplace($mot_interdit, $remplacement, $text);
        }
        return $text;

    }

}