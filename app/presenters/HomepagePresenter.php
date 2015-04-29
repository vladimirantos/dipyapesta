<?php

namespace App\Presenters;

use Nette,
    App\Model;

/**
 * Homepage presenter.
 */
class HomepagePresenter extends BasePresenter {

    public function renderKontakt() {

        $this->setActive("kontakt");
    }

    public function renderDefault() {

        $this->setActive("homepage");
    }

}
