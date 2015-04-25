<?php

namespace App\AdminModule\Presenters;

use App\Model;

class AdminPresenter extends \App\Presenters\BasePresenter {

    /**
     * @var Model\IngredientsManager @inject
     */
    public $ingredients;
    private $active = array("novinky" => "", "produkty" => "", "recepty" => "", "uzivatele" => "");

    public function startup(){
        parent::startup();
        if(!$this->getUser()->isLoggedIn())
            $this->redirect(":Sign:in");
    }

    public function setActive($id) {
        $this->active[$id] = "active";
    }

    public function afterRender() {
        parent::afterRender();
        $this->template->active = $this->active;
        $session = $this->session->getSection("ingredients");
        if (!is_null($session->title) and ($this->presenter->name!="Admin:Recepty" )) {
            $this->ingredients->delete($session->title);
        }
    }

}
