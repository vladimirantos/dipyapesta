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

    public function __construct() {
        parent::__construct();
        $this->setActive("recipes");
    }

}
