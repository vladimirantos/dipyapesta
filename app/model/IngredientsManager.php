<?php

namespace App\Model;

use Nette;

class IngredientsManager extends ModelContainer {

    const table = "ingredients",
            id = "id_ingredient",
            recipes = "recipes";

    public function getAll($title) {
        return $this->database->table(self::table)->where(self::recipes, $title)->fetchAll();
    }

    public function delete($title) {
        $this->database->table(self::table)->where(self::recipes, $title)->delete();
    }

    public function deleteOne($id) {
        $this->database->table(self::table)->where(self::id, $id)->delete();
    }

    public function add($data, $title) {
        $data['recipes'] = $title;
        $this->database->table(self::table)->insert($data);
    }

    public function changeTitle($old, $new) {
        $data = array('recipes' => $new);
        $this->database->table(self::table)->where(self::recipes, $old)->update($data);
    }

}
