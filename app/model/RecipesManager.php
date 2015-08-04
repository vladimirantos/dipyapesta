<?php

namespace App\Model;

use Nette;

class RecipesManager extends ModelContainer {

    const table = "recipes",
            id = "id_recipe";

    private $galleryPath = __DIR__ . "/../../www/images/recipes/";
    private $path;

    public function getAll() {
        return $this->database->table(self::table)->fetchAll();
    }

    public function getAllByLang($lang) {
        return $this->database->table(self::table)->where("language", $lang)->fetchAll();
    }

    public function get($id, $language) {
        return $this->database->table(self::table)->where(array(self::id => $id, "language" => $language))->fetch();
    }

    public function getAllNewsPair() {
        return $this->database->table(self::table)->fetchPairs("id_recipe", "title");
    }

    public function delete($id, $language) {
        $this->database->table(self::table)->where(array(self::id => $id, "language" => $language))->delete();
    }

    public function add($data) {
        try {
            if (empty($data['id_recipe'])) {
                $data['id_recipe'] = $this->createId(self::table, self::id);
            }
            $image = null;
            if (isset($data->image) and ! empty($data->image->name)) {
                $image = $data->image;
                unset($data->image);
            }
            if (isset($data->translate) and $data->translate != null)
                $data->id_recipe = $data->translate;
            unset($data->translate);

            $this->database->table(self::table)->insert($data);

            if ($image != null)
                $this->addMainImage($image, $data->id_recipe, $data->language);
            return $data['id_recipe'];
        } catch (\PDOException $e) {
            if ($e->getCode() == 23000)
                throw new \Nette\InvalidArgumentException("Recept s tímto názvem již existuje");
            else
                throw new \Exception($e->getMessage());
        }
    }

    public function update($data) {
        try {
            $image = null;
            if (isset($data->image) and ! empty($data->image->name)) {
                $image = $data->image;
            }
            unset($data->image);
            $this->database->table(self::table)->where(array(self::id => $data['id_recipe'], "language" => $data['language']))->update($data);
            if ($image != null)
                $this->editMainImage($image, $data['id_recipe'], $data['language']);
        } catch (\PDOException $e) {
            if ($e->getCode() == 23000)
                throw new \Nette\InvalidArgumentException("Recept s tímto názvem již existuje");
            else
                throw new \Exception($e->getMessage());
        }
    }

    public function createEmpty($id, $lang) {
        $data = array("language" => $lang, "title" => "Rozpracované!", "description" => "Rozpracovaný recept", "id_recipe" => $id);
        return $this->add($data);
    }

    public function getRandom() {
        return $this->database->table(self::table)->order("RAND()")->limit(1)->fetch();
    }

################################################################################
##############################    Obrázky    ###################################
################################################################################

    public function exists($filename) {
        return file_exists($filename);
    }

    private function createImageName($oldName) {
        $extension = explode(".", $oldName);
        $extension = $extension[count($extension) - 1]; //koncovka souboru
        $filename = null;
        do {
            $filename = $this->randString(20) . "." . $extension;
        } while ($this->exists($filename));
        return $filename;
    }

    public function addMainImage(\Nette\Http\FileUpload $image, $id, $lang) {
        $imageName = $this->createImageName($image->name);
        $this->path = $this->galleryPath . $imageName;
        $image->move($this->path);
        $data = array("id_recipe" => $id, "image" => $imageName, "language" => $lang);
        $this->update($data);
    }

    public function editMainImage(\Nette\Http\FileUpload $image, $id, $lang) {
        $old = $this->get($id, $lang);
        $this->deleteImage($this->galleryPath . $old->image);
        $this->addMainImage($image, $id, $lang);
    }

    public function deleteImage($path) {
        if (file_exists($path) and ! is_dir($path))
            unlink($path);
    }

}
