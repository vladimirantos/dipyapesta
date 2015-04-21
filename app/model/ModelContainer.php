<?php

namespace App\Model;

use Nette;

/**
 * Společný předek všech modelů.
 *
 * @author Vladimír Antoš
 * @package caravans_model
 * @abstract
 */
abstract class ModelContainer extends \Nette\Object {

    /** @var \Nette\Database\Context */
    protected $database;

    public function __construct(\Nette\Database\Context $db) {
        $this->database = $db;
    }

    /**
     * Vygeneruje náhodné číslo zadané délky.
     * @param type $length
     * @return int
     */
    protected function randNumber($length) {
        return (int) \Nette\Utils\Strings::random($length, '0-9');
    }

    /**
     * Vrací náhodný řetězec
     * @param int $length
     * @return string
     */
    protected function randString($length) {
        return Nette\Utils\Strings::random($length);
    }

    /**
     * Vytvoří a zkontroluje unikátní id v tabulce
     * @param String $table název tabulky
     * @param String $idColumn sloupec primary key (default='id')
     * @param int $length  délka vytvořeného id (default=4)
     * @return int $id
     */
    public function createId($table, $idColumn="id",$length=4) {
        do {
            $id = $this->randNumber($length);
            $isFree = $this->database->table($table)->where($idColumn, $id)->count();
        } while ($isFree != 0);
        return $id;
    }

}
