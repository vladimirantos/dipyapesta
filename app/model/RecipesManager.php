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

    public function get($id, $language) {
        return $this->database->table(self::table)->where(array(self::id => $id, "language" => $language))->fetch();
    }

    public function getAllNewsPair(){
        return $this->database->table(self::table)->fetchPairs("id_recipe", "title");
    }

    public function delete($id, $language) {
        $this->database->table(self::table)->where(array(self::id => $id, "language" => $language))->delete();
    }

    public function add($data) {
        try {
            $image = null;
            if ($data->image->isImage()) {
                $image = $data->image;
                unset($data->image);
            }
            if($data->translate != null)
                $data->id_recipe = $data->translate;
            unset($data->translate);

            $this->database->table(self::table)->insert($data);

            if ($image != null)
                $this->addMainImage($image, $data->title);
        } catch (\PDOException $e) {
            if ($e->getCode() == 23000)
                throw new \Nette\InvalidArgumentException("Recept s tímto názvem již existuje");
            else
                throw new \Exception($e->getMessage());
        }
    }

    public function update($data) {
        try {
            $this->database->table(self::table)->where(self::id, $data['title'])->update($data);
        } catch (\PDOException $e) {
            if ($e->getCode() == 23000)
                throw new \Nette\InvalidArgumentException("Recept s tímto názvem již existuje");
            else
                throw new \Exception($e->getMessage());
        }
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

    public function addMainImage(\Nette\Http\FileUpload $image, $title) {
        $imageName = $this->createImageName($image->name);
        $this->path = $this->galleryPath . $imageName;
        $image->move($this->path);
        $data = array("title" => $title, "image" => $imageName);
        $this->update($data);
    }

    public function editMainImage(\Nette\Http\FileUpload $image, $title) {
        $old = $this->get($title);
        $this->deleteImage($this->galeryPath . $old->main_image);
        $this->addMainImage($image, $title);
    }

    public function deleteImage($path) {
        if (file_exists($path))
            unlink($path);
    }

}
