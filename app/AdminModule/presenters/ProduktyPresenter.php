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

    public function renderDefault() {
        $this->template->products = $this->product->getAll();
    }

    public function renderDetail($id, $language) {
        $this->template->product = $this->product->get($id, $language);
    }

    public function renderEdit($id, $language) {
        $data = $this->product->get($id, $language);
        $this->template->product = $data;
        $this['edit']->setDefaults($data);
    }

    public function handleDelete($id, $language){
        $this->product->delete($id, $language);
        $this->flashMessage("Produkt byl úspěšně odstraněn", "success");
        $this->redirect("Produkty:");
    }

    protected function createComponentCreateNew() {
        $form = new UI\Form();
        $form->addSelect("translate", "Překlad článku: ", $this->product->getAllNewsPair())->setPrompt("Vyber produkt který chceš překládat");
        $form->addSelect("language", "Jazyk:", Model\Languages::toArray());
        $form->addText("title", "Nadpis:")
                ->setRequired("Zadejte prosím nadpis");

        $form->addTextArea("short_description", "Krátký popis:");
        $form->addTextArea("description", "Popis:")
                ->setRequired("Zadejte prosím popis produktu");
        $form->addTextArea("preparation", "Příprava:");
        $form->addTextArea("products", "Produkty");
        $form->addUpload("main_image", "Hlavní obrázek");
        $form->addText("words", "Klíčová slova")
                ->setAttribute("placeholder", "Klíčová slova musejí být odděleny čárkou!");
        $form->addText("html", "Popis pro html");
        $form->addSubmit("submit", "Vytvořit");

        $form->onSubmit[] = $this->createNew;

        return $form;
    }

    protected function createComponentEdit() {
        $form = new UI\Form();
        $form->addSelect("language", "Jazyk:", Model\Languages::toArray());
        $form->addText("title", "Nadpis:")
                ->setRequired("Zadejte prosím nadpis");

        $form->addTextArea("short_description", "Krátký popis:");
        $form->addTextArea("description", "Popis:")
                ->setRequired("Zadejte prosím popis produktu");
        $form->addTextArea("preparation", "Příprava:");
        $form->addTextArea("products", "Produkty");
        $form->addUpload("main_image", "Hlavní obrázek");
        $form->addText("words", "Klíčová slova")
                ->setAttribute("placeholder", "Klíčová slova musejí být odděleny čárkou!");
        $form->addText("html", "Popis pro html");
        
        $form->addHidden("id_product");
        $form->addSubmit("submit", "Vytvořit");

        $form->onSubmit[] = $this->update;

        return $form;
    }

    public function createNew(UI\Form $form) {
        $data = $form->getValues();
        try {
            $id = $this->product->add($data);
            $this->flashMessage("Produkt byl úspěšně vytvořen", 'success');
            $this->redirect("Produkty:detail", $id, $data->language);
        } catch (\Nette\InvalidArgumentException $e) {
            $this->flashMessage($e->getMessage(), 'error');
            $this->redrawControl("messages");
        }
    }

    public function update(UI\Form $form) {
        $data = $form->getValues();
        $title = $data->title;
        $id = $data->id_product;
        unset($data->id_product);
        if (isset($data->main_image) && $data->main_image->isImage()) {
            $image = $data->main_image;
        }
        unset($data->main_image);
        try {
            b($this->params);
            $this->product->update($data, $id, $this->params["language"]);
            if (isset($image)) {
                $this->product->editMainImage($image, $id, $data->language);
            }
            $this->flashMessage("Produkt byl úspěšně upraven", 'success');
            $this->redirect("Produkty:detail", $this->params["id"], $data->language);
        } catch (\Nette\InvalidArgumentException $e) {
            $this->flashMessage($e->getMessage(), 'error');
            $this->redrawControl("messages");
        }
    }

}
