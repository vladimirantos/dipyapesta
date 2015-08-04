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

    public function startup() {
        parent::startup();
                $this->ingredients->sort();

    }

    public function renderDefault() {
        $this->setTitle($this->trans('title', 'recipes'));
        $this->template->recipes = $this->recipes->getAllByLang($this->locale);
    }

    public function renderDetail($id) {
        $recipe = $this->recipes->get($id, $this->locale);
        $this->template->recipe = $recipe;
        $this->template->ingredients = $this->ingredients->getAll($id, $this->locale);
        if (!empty($recipe->youtube)) {
            $code = explode('=', $recipe->youtube);
            $this->template->code = $code[1];
        }
        $this->setTitle($this->template->recipe->title);
        $this->setKeyWords($this->template->recipe->words);
        $this->setHtmlDesc($this->template->recipe->html);
    }

}
