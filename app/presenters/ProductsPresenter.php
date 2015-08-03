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

    public function startup(){
        parent::startup();
    }

    public function renderDefault() {
        $this->setTitle($this->trans('title'));
        $this->template->products = $this->products->getAllByLang($this->locale);
    }
    
    public function renderDetail($id){
        $this->template->product = $this->products->get($id, $this->locale);
        $this->setTitle($this->template->product->title);
        $this->setKeyWords($this->template->product->words);
        $this->setHtmlDesc($this->template->product->html);
    }

}
