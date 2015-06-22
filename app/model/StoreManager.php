<?php
/**
 * Created by PhpStorm.
 * User: Vladimír
 * Date: 3. 6. 2015
 * Time: 16:01
 */

namespace App\Model;


class StoreManager extends ModelContainer{

    const TABLE = "stores";

    public function insert(array $data){
        $this->database->table(self::TABLE)->insert($data);
    }

    public function update(array $data, $id){
        $this->database->table(self::TABLE)->where("id_store", $id)->update($data);
    }

    public function getAll(){
        return $this->database->table(self::TABLE);
    }

    public function get($id){
        return $this->database->table(self::TABLE)->where("id_store", $id)->fetch();
    }

    public function delete($id){
        $this->database->table(self::TABLE)->where("id_store", $id)->delete();
    }
}