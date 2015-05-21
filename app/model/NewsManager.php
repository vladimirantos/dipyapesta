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

    public $galleryPath = __DIR__ . "/../../www/images/gallery/";
    public $path;

    public function getAll() {
        return $this->database->table(self::table)->fetchAll();
    }

    public function getAllByLang($lang) {
        return $this->database->table(self::table)->where('language', $lang)->order("date DESC")->fetchAll();
    }

    public function get($id, $language) {
        return $this->database->table(self::table)->where(array(self::id => $id, "language" => $language))->fetch();
    }

    public function add($data) {
        try {
            if ($data->main_image->isImage()) {
                $image = $data->main_image;
                unset($data->main_image);
            }
            if ($data->translate != null) {
                $data->id_article = $data->translate;
            }
            unset($data->translate);

            $this->database->table(self::table)->insert($data);
            $id = $this->database->table(self::table)->where(array("title" => $data->title, "language" => $data->language))->fetch()->id_article;
            if (isset($image)) {
                $this->addMainImage($image, $id, $data->language);
            }
            return $id;
        } catch (\PDOException $e) {
            if ($e->getCode() == 23000)
                throw new \Nette\InvalidArgumentException("Novinka s tímto nadpisem již existuje");
            else
                throw new \Exception($e->getMessage());
        }
    }

    public function edit($data) {
        try {
            if ($data->main_image->isImage()) {
                $image = $data->main_image;
            }
            unset($data->main_image);
            $oldLanguage = $data->oldLanguage;
            unset($data->oldLanguage);
            b($data);
            //$data['date'] = $this->database->query("SELECT CURRENT_TIMESTAMP as 'date' FROM dual")->fetch()->date;
            $this->database->table(self::table)->where(array(self::id => $data->id_article, "language" => $oldLanguage))->update($data);
            if (isset($image) && $image->getName() != null) {
                $this->editMainImage($image, $data->id_article, $data->language);
            }
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
    public function addMainImage(\Nette\Http\FileUpload $image, $id, $lang) {
        $imageName = $this->createImageName($image->name);
        $this->path = $this->galleryPath . $imageName;
        $image->move($this->path);
        $data['main_image'] = $imageName;
        $this->database->table(self::table)->where(self::id, $id)->where("language", $lang)->update($data);
    }

    public function editMainImage(\Nette\Http\FileUpload $image, $id, $lang) {
        $old = $this->get($id, $lang);
        $this->deleteImage($this->galleryPath . $old->main_image);
        $this->addMainImage($image, $id, $lang);
    }

    public function deleteImage($path) {
        if (file_exists($path) and ! is_dir($path))
            unlink($path);
    }

}
