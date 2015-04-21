<?php

namespace App\Model;

use Nette;

class RecipesManager extends ModelContainer {

    const table = "recipes",
            id = "title";

    public function getAll() {
        return $this->database->table(self::table)->fetchAll();
    }

    public function get($title) {
        return $this->database->table(self::table)->where(self::id, $title)->fetch();
    }

    public function delete($title){
        $this->database->table(self::table)->where(self::id,$title)->delete();
    }
    public function add($data){
        $this->database->table(self::table)->insert($data);
    }
}
