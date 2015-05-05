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
    public function renderDefault(){
        $this->template->news = $this->news->getAllByLang('cs');
    }
}
