<?php

namespace App\Model;

use Nette;

/**
 * Description of NewsManager
 *
 * @author Bruno
 */
class ProductManager extends ModelContainer {

    const table = "products",
            id = "title";

    public $galleryPath = __DIR__."/../../www/images/gallery/";
    public $path;

    public function getAll() {
        return $this->database->table(self::table)->fetchAll();
    }

    public function get($title) {
        return $this->database->table(self::table)->where(self::id, $title)->fetch();
    }

    public function delete($title) {
        $this->database->table(self::table)->where(self::id, $title)->delete();
    }

    public function update($data) {
        if (isset($data->main_image)) {
            $image = $data->main_image;
            unset($data->main_image);
        }
        $title = $data['title'];
        unset($data['title']);
        try {
            $this->database->table(self::table)->where(self::id, $title)->update($data);
        } catch (\PDOException $e) {
            if ($e->getCode() == 23000)
                throw new \Nette\InvalidArgumentException("Produkt s tímto nadpisem již existuje");
            else
                throw new \Exception($e->getMessage());
        }
    }

    public function add($data) {
        if (isset($data->main_image)) {
            $image = $data->main_image;
            unset($data->main_image);
        }
        $title = $data->title;
        try {
            $this->database->table(self::table)->insert($data);
            if (isset($image))
                $this->addMainImage($image, $title);
        } catch (\PDOException $e) {
            if ($e->getCode() == 23000)
                throw new \Nette\InvalidArgumentException("Produkt s tímto nadpisem již existuje");
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
        $data = array("title" => $title, "main_image" => $imageName);
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
