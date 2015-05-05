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

    /**
     * @var Model\ProductManager @inject
     */
    public $products;

    public function __construct() {
        parent::__construct();
        $this->setActive("products");
    }

    public function renderDefault() {
        $this->template->products = $this->products->getAllByLang("cs");
    }
    
    public function renderDetail($id){
        $this->template->product = $this->products->get($id, "cs");
    }

}
