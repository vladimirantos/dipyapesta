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
            id = "id_product";

    public $galleryPath = __DIR__ . "/../../www/images/gallery/";
    public $path;

    public function getAll() {
        return $this->database->table(self::table)->fetchAll();
    }

    public function getAllByLang($lang) {
        return $this->database->table(self::table)->where("language", $lang)->order("order")->fetchAll();
    }

    public function get($id, $language) {
        return $this->database->table(self::table)->where(array(self::id => $id, "language" => $language))->fetch();
    }

    public function getAllNewsPair() {
        return $this->database->table(self::table)->fetchPairs("id_product", "title");
    }

    public function delete($id, $language) {
        $this->database->table(self::table)->where(array(self::id => $id, "language" => $language))->delete();
    }

    public function update($data, $id, $language) {
        try {
            $this->database->table(self::table)->where(array(self::id => $id, "language" => $language))->update($data);
        } catch (\PDOException $e) {
            if ($e->getCode() == 23000)
                throw new \Nette\InvalidArgumentException("Produkt s tímto nadpisem již existuje");
            else
                throw new \Exception($e->getMessage());
        }
    }

    public function add($data) {
        $image = null;
        if ($data->main_image->isImage()) {
            $image = $data->main_image;
            unset($data->main_image);
        }

        if ($data->translate != null) {
            $data->id_product = $data->translate;
        }
        unset($data->translate);
        $title = $data->title;
        try {
            $id = $this->createId("products", "id_product");
            $data->id_product = $id;
            $this->database->table(self::table)->insert($data);
            if ($image != null)
                $this->addMainImage($image, $data, $id);
        } catch (\PDOException $e) {
            if ($e->getCode() == 23000)
                throw new \Nette\InvalidArgumentException("Produkt s tímto nadpisem již existuje");
            else
                throw new \Exception($e->getMessage());
        }
        return $id;
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

    /**
     * TODO: zmenšení obrázku
     * @param Nette\Http\FileUpload $image
     * @param $title
     * @throws \Exception
     */
    public function addMainImage(\Nette\Http\FileUpload $image, $data, $id, $lang = null) {
        $imageName = $this->createImageName($image->name);
        $this->path = $this->galleryPath . $imageName;
        $image->move($this->path);
        $data['main_image'] = $imageName;
        if (is_null($lang)) {
            $this->update($data, $id, $data->language);
        } else {
            $this->update($data, $id, $lang);
        }
    }

    public function editMainImage(\Nette\Http\FileUpload $image, $id, $lang) {
        $old = $this->get($id, $lang);
        $this->deleteImage($this->galleryPath . $old->main_image);
        $data = array();
        $this->addMainImage($image, $data, $id, $lang);
    }

    public function deleteImage($path) {
        if (file_exists($path) and !is_dir($path))
            unlink($path);
    }

}
