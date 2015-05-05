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

    public function getAllByLang($lang) {
        return $this->database->table(self::table)->where('language',$lang)->fetchAll();
    }

    public function get($id, $language) {
        return $this->database->table(self::table)->where(array(self::id => $id, "language" => $language))->fetch();
    }

    public function add($data) {
        try {
            if ($data->translate != null) {
                $data->id_article = $data->translate;
            }
            unset($data->translate);

            $this->database->table(self::table)->insert($data);
            return $this->database->table(self::table)->where(array("title" => $data->title, "language" => $data->language))->fetch()->id_article;
        } catch (\PDOException $e) {
            if ($e->getCode() == 23000)
                throw new \Nette\InvalidArgumentException("Novinka s tímto nadpisem již existuje");
            else
                throw new \Exception($e->getMessage());
        }
    }

    public function edit($data) {
        try {
            $oldLanguage = $data->oldLanguage;
            unset($data->oldLanguage);
            $data['date'] = $this->database->query("SELECT CURRENT_TIMESTAMP as 'date' FROM dual")->fetch()->date;
            $this->database->table(self::table)->where(self::id, $data->id_article)->where("language",$oldLanguage)->update($data);
        } catch (\PDOException $e) {
            if ($e->getCode() == 23000)
                throw new \Nette\InvalidArgumentException("Novinka s tímto nadpisem již existuje");
            else
                throw new \Exception($e->getMessage());
        }
    }

    public function delete($id, $language) {
        $this->database->table(self::table)->where(array(self::id => $id, "language" => $language))->delete();
    }

    public function getAllNewsPair() {
        return $this->database->table(self::table)->fetchPairs("id_article", "title");
    }

}
