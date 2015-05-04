<?php

namespace App\AdminModule\Presenters;

use App\Model;

class AdminPresenter extends \App\Presenters\BasePresenter {

    /**
     * @var Model\IngredientsManager @inject
     */
    public $ingredients;
    private $active = array("novinky" => "", "produkty" => "", "recepty" => "", "odhlasit" => "");

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
        $session = $this->session->getSection("recipe");
        if (!is_null($session->id_recipe) and ($this->presenter->name!="Admin:Recepty" or ($this->presenter->action!="new" and $this->presenter->action!="edit"))) {
            $session->id_recipe=null;
            $session->language=null;
        }
    }

}
