<?php

namespace App\AdminModule\Presenters;

use App\Model;

/**
 * Description of NewsPresenter
 *
 * @author Bruno
 */
class NewsPresenter extends AdminPresenter {

    /**
     * @var Model\NewsManager @inject
     */
    public $news;

    public function __construct() {
        parent::__construct();
        $this->setActive("novinky");
    }

    public function renderDefault() {
        $this->template->news = $this->news->getAll();
    }
    public function renderDetail($id){
        $this->template->new = $this->news->get($id);
    }

}
