<?php

namespace App\Presenters;

use Nette,
    App\Model;

/**
 * Description of NewsPresenter
 *
 * @author Bruno
 */
class NewsPresenter extends BasePresenter {

    /**
     * @var Model\NewsManager @inject
     */
    public $news;

    public function __construct() {
        parent::__construct();
        $this->setActive("news");
    }

    public function startup(){
        parent::startup();
    }

    public function renderDefault(){
        $this->setTitle("Novinky");
        $this->template->news = $this->news->getAllByLang('cs');
    }
}
