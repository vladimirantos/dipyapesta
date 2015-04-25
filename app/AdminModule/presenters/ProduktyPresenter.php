<?php

namespace App\AdminModule\Presenters;

use App\Model,
    \Nette\Application\UI;

class ProduktyPresenter extends AdminPresenter {

    /**
     * @var Model\ProductManager @inject
     */
    public $product;

    public function __construct() {
        parent::__construct();
        $this->setActive("produkty");
    }

    public function startup(){
        parent::startup();
    }

    public function renderDefault() {
        $this->template->products = $this->product->getAll();
    }

    public function renderDetail($title) {
        $this->template->product = $this->product->get($title);
    }

    public function renderEdit($title) {
        $data = $this->product->get($title);
        $this->template->product = $data;
        $this['edit']->setDefaults($data);
    }

    public function handleDelete($title) {
        $this->product->delete($title);
        $this->flashMessage("Produkt byl úspěšně odstraněn", "success");
        $this->redirect("Produkty:");
    }

    protected function createComponentCreateNew() {
        $form = new UI\Form();

        $form->addText("title", "Nadpis:")
                ->setRequired("Zadejte prosím nadpis");

        $form->addTextArea("short_description", "Krátký popis:");
        $form->addTextArea("description", "Popis:")
                ->setRequired("Zadejte prosím popis produktu");
        $form->addTextArea("preparation", "Příprava:")
                ->setRequired("Popiště prosím přípravu produktu");

        $form->addUpload("main_image", "Hlavní obrázek");

        $form->addSubmit("submit", "Vytvořit");

        $form->onSubmit[] = $this->createNew;

        return $form;
    }

    protected function createComponentEdit() {
        $form = new UI\Form();

        $form->addText("title", "Nadpis:")
                ->setRequired("Zadejte prosím nadpis");

        $form->addTextArea("short_description", "Krátký popis:");
        $form->addTextArea("description", "Popis:")
                ->setRequired("Zadejte prosím popis produktu");
        $form->addTextArea("preparation", "Příprava:")
                ->setRequired("Popiště prosím přípravu produktu");

        $form->addUpload("main_image", "Hlavní obrázek");

        $form->addSubmit("submit", "Vytvořit");

        $form->onSubmit[] = $this->update;

        return $form;
    }

    public function createNew(UI\Form $form) {
        $data = $form->getValues();
        try {
            $this->product->add($data);
            $this->flashMessage("Produkt byl úspěšně vytvořen", 'success');
            $this->redirect("Produkty:detail", $data->title);
        } catch (\Nette\InvalidArgumentException $e) {
            $this->flashMessage($e->getMessage(), 'error');
            $this->redrawControl("messages");
        }
    }

    public function update(UI\Form $form) {
        $data = $form->getValues();
        $title = $data->title;
        try {
            $this->product->update($data);
            $this->flashMessage("Produkt byl úspěšně upraven", 'success');
            $this->redirect("Produkty:detail", $title);
        } catch (\Nette\InvalidArgumentException $e) {
            $this->flashMessage($e->getMessage(), 'error');
            $this->redrawControl("messages");
        }
    }

}
