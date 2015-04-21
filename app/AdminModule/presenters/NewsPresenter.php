<?php

namespace App\AdminModule\Presenters;

use App\Model,
    \Nette\Application\UI;

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

    public function renderDetail($id) {
        $this->template->new = $this->news->get($id);
    }

    public function renderNew() {
        
    }

    protected function createComponentCreateNew() {
        $form = new UI\Form();
        $form->addText("title", "Nadpis:")
                ->setRequired("Zvolte prosím nadpis novinky");
        $form->addTextArea("content", "Obsah:")
                ->setRequired("Zadejte prosím obsah novinky");
        $form->addSubmit("submit", "Vytvořit");
        $form->onSubmit[] = $this->createNew;
        return $form;
    }

    public function createNew(UI\Form $form) {
        $values = $form->getValues();
        try {
            $done = $this->news->add($values);
            $this->flashMessage("Novinka byla úspěšně vytvořena", "success");
            $this->redirect("News:detail", $done);
        } catch (\Nette\InvalidArgumentException $e) {
            $this->flashMessage($e->getMessage(), "error");
            $this->redrawControl("messages");
        }
    }

}
