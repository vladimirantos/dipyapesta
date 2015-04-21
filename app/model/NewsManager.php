<?php

namespace App\Model;

use Nette;

/**
 * Description of NewsManager
 *
 * @author Bruno
 */
class NewsManager extends ModelContainer {

    const table = "articles",
            id = "id_article";

    public function getAll() {
        return $this->database->table(self::table)->fetchAll();
    }

    public function get($id) {
        return $this->database->table(self::table)->where(self::id, $id)->fetch();
    }

    public function add($data) {
        try {
            $this->database->table(self::table)->insert($data);
            return $this->database->table(self::table)->where("title", $data->title)->fetch()->id_article;
        } catch (\PDOException $e) {
            if ($e->getCode() == 23000)
                throw new \Nette\InvalidArgumentException("Novinka s tímto nadpisem již existuje");
            else
                throw new \Exception($e->getMessage());
        }
    }

}
