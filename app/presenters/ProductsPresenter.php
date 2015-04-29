<?php

namespace App\Presenters;

use Nette,
    App\Model;

/**
 * Description of ProductsPresenter
 *
 * @author Bruno
 */
class ProductsPresenter extends BasePresenter {

    public function __construct() {
        parent::__construct();
        $this->setActive("products");
    }

}
