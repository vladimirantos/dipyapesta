<?php

namespace App\AdminModule\Presenters;

use App\Model,
    \Nette\Application\UI;

/**
 * Description of NewsPresenter
 *
 * @author Bruno
 */
class NovinkyPresenter extends AdminPresenter {

    /**
     * @var Model\NewsManager @inject
     */
    public $news;

    public function __construct() {
        parent::__construct();
        $this->setActive("novinky");
    }

    public function startup(){
        parent::startup();
    }

    public function renderDefault() {
        $this->template->news = $this->news->getAll();
    }

    public function renderDetail($id) {
        $this->template->new = $this->news->get($id);
    }

    public function renderEdit($id) {
        $data = $this->news->get($id);
        $this['edit']->setDefaults($data);
    }

    public function handleDelete($id) {
        $this->news->delete($id);
        $this->flashMessage("Novinka byla úspěšně odstraněna","success");
        $this->redirect("Novinky:");
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
            $this->redirect("Novinky:detail", $done);
        } catch (\Nette\InvalidArgumentException $e) {
            $this->flashMessage($e->getMessage(), "error");
            $this->redrawControl("messages");
        }
    }

    protected function createComponentEdit() {
        $form = new UI\Form();
        $form->addText("title", "Nadpis:")
                ->setRequired("Zvolte prosím nadpis novinky");
        $form->addTextArea("content", "Obsah:")
                ->setRequired("Zadejte prosím obsah novinky");
        $form->addHidden("id_article");
        $form->addSubmit("submit", "Upravit");
        $form->onSubmit[] = $this->edit;
        return $form;
    }

    public function edit(UI\Form $form) {
        try {
            $this->news->edit($form->getValues());
            $this->flashMessage("Novinka byla úspěšně upravena", "success");
            $this->redirect("Novinky:detail", $form->getValues()->id_article);
        } catch (\Nette\InvalidArgumentException $e) {
            $this->flashMessage($e->getMessage(), "error");
            $this->redrawControl("messages");
        }
    }

}
