<?php
/**
 * Created by PhpStorm.
 * User: Vladimír
 * Date: 1. 5. 2015
 * Time: 9:10
 */

namespace App\Model;


class Languages {
    const CS = "Čeština";
    const EN = "Angličtina";
    const DE = "Němčina";

    public static function toArray(){
        return array("cs" => self::CS, "en" => self::EN, "de" => self::DE);
    }
}