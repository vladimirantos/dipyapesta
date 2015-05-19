<?php

namespace App\Presenters;

use Nette,
    App\Model;

/**
 * Homepage presenter.
 */
class HomepagePresenter extends BasePresenter {

    public function startup(){
        parent::startup();
    }

    public function renderKontakt() {
        $this->setTitle("Kontakt");
        $this->setActive("kontakt");
    }

    public function renderDefault() {
        $this->setActive("homepage");
    }

}
