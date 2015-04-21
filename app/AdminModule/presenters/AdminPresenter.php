<?php

namespace App\AdminModule\Presenters;

class AdminPresenter extends \App\Presenters\BasePresenter {

    private $active = array("novinky"=>"","produkty"=>"","recepty"=>"","uzivatele"=>"");

    public function setActive($id) {
        $this->active[$id] = "active";
    }

    public function afterRender() {
        parent::afterRender();
        $this->template->active = $this->active;
    }

}
