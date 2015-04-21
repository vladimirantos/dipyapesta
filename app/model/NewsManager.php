<?php

namespace App\Model;

use Nette;

/**
 * Description of NewsManager
 *
 * @author Bruno
 */
class NewsManager extends Nette\Object {

    const table = "articles";

    /** @var Nette\Database\Context */
    private $database;

    public function __construct(\Nette\Database\Context $db) {
        $this->database = $db;
    }

    public function getAll() {
        return $this->database->table(self::table)->fetchAll();
    }

    public function get($id) {
        return $this->database->table(self::table)->where("id_article",$id)->fetch();
    }

}
