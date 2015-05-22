<?php

namespace App\Presenters;

use Nette,
    App\Model;

/**
 * Base presenter for all application presenters.
 */
abstract class BasePresenter extends Nette\Application\UI\Presenter {

    /**
     * @var Model\RecipesManager @inject
     */
    public $recipes;
    private $active = array("homepage" => "", "products" => "", "news" => "", "recipes" => "", "kontakt" => "");

    public function startup(){
        parent::startup();
        $this->template->title = "Dip'it";
    }

    public function setActive($presenterMame) {
        $this->active[$presenterMame] = "active";
    }

    public function setTitle($title){
        $this->template->title = $title;
    }

    public function afterRender() {
        parent::afterRender();
        $this->template->active = $this->active;
        $this->template->randomRecipe = $this->recipes->getRandom();
    }

}
