<?php

namespace App\Model;

use Nette;

class IngredientsManager extends ModelContainer {

    const table = "ingredients",
            id = "id_ingredient",
            recipes = "recipes";

    public function getAll($id, $language) {
        return $this->database->table(self::table)->where(array("recipes" => $id, "language" => $language))->fetchAll();
    }

    public function delete($title) {
        $this->database->table(self::table)->where(self::recipes, $title)->delete();
    }

    public function deleteOne($id) {
        $this->database->table(self::table)->where(self::id, $id)->delete();
    }

    public function add($data, $id_recipe) {
        $data->recipes = $id_recipe;
        try {
            $this->database->table(self::table)->insert($data);
        } catch (\PDOException $e) {
            if ($e->getCode() == 23000)
                throw new \Nette\InvalidArgumentException("Nelze přidat dvě ingredience se stejným názvem k jednomu receptu");
            else
                throw new \Exception($e->getMessage());
        }
    }

    public function changeTitle($old, $new) {
        $data = array('recipes' => $new);
        $this->database->table(self::table)->where(self::recipes, $old)->update($data);
    }

}
