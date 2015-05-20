<?php

namespace App\Presenters;

use Nette,
    App\Model;

/**
 * Description of RecipesPresenter
 *
 * @author Bruno
 */
class RecipesPresenter extends BasePresenter {

    /**
     * @var Model\RecipesManager @inject
     */
    public $recipes;

    /**
     * @var Model\IngredientsManager @inject
     */
    public $ingredients;

    public function __construct() {
        parent::__construct();
        $this->setActive("recipes");
    }
    public function startup(){
        parent::startup();
    }
    public function renderDefault() {
        $this->setTitle("Recepty");
        $this->template->recipes = $this->recipes->getAllByLang("cs");
    }

    public function renderDetail($id) {
        $this->template->recipe = $this->recipes->get($id,"cs");
        $this->template->ingredients = $this->ingredients->getAll($id, "cs");
        $this->setTitle($this->template->recipe->title);
    }

}
