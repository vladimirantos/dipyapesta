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

    public function __construct() {
        parent::__construct();
        $this->setActive("news");
    }

}
