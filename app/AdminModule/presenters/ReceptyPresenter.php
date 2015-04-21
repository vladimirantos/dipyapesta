<?php

namespace App\AdminModule\Presenters;

use Nette,
    \App\Model,
    Nette\Application\UI;

class ReceptyPresenter extends AdminPresenter {

    /**
     * @var Model\RecipesManager @inject
     */
    public $recipes;

    

    public function renderDefault() {
        $this->template->recipes = $this->recipes->getAll();
    }

    public function renderDetail($title) {
        $this->template->recipe = $this->recipes->get($title);
        $this->template->ingredients = $this->ingredients->getAll($title);
    }

    public function renderNew() {
        $session = $this->session->getSection("ingredients");
        if (isset($session->title))
            $this->template->ingredients = $this->ingredients->getAll($session->title);
        else
            $this->template->ingredients = array();
    }

    public function handleDelete($title) {
        $this->recipes->delete($title);
        $this->ingredients->delete($title);
        $this->flashMessage("Recept byl úspěšně odstraněn", "success");
        $this->redirect("Recepty:");
    }

    public function handleDeleteIngredient($id) {
        $this->ingredients->deleteOne($id);
        $this->flashMessage("Ingredience byla úspěšně odstraněna", "success");
        $this->redrawControl("ingredients");
    }

    protected function createComponentCreateNew() {
        $form = new UI\Form();
        $form->addText("title", "Název:")
                ->setRequired("Zadejte prosím název receptu");

        $form->addTextArea("description", "Popis:")
                ->setRequired("Popiště prosím recept");

        $form->addSubmit("submit", "Vytvořit recept");

        $form->addUpload("image", "Obrázek:");
        $form->onSubmit[] = $this->createNew;
        return $form;
    }

    protected function createComponentIngredients() {
        $form = new UI\Form();
        $form->addText("ingredient", "Ingredience:")
                ->setRequired("Zadejte prosím ingredienci");

        $form->addText("quantity", "Množství:")
                ->setRequired("Zadejte prosím kolik je potřeba této ingredience");

        $form->addText("unit", "Jednotka:")
                ->setRequired("Zadejte prosím jednotku této ingredience");

        $form->addSubmit("submit", "Přidat ingredienci");
        $form->onSubmit[] = $this->newIngredient;
        return $form;
    }

    public function createNew(UI\Form $form) {
        $data = $form->getValues();
        $this->recipes->add($data);
        $session = $this->session->getSection("ingredients");
        $this->ingredients->changeTitle($session->title, $data->title);
        $session->title=null;
        $this->flashMessage("Recept byl úspěšně vytvořen");
        $this->redirect("Recepty:detail",$data->title);
    }

    public function newIngredient(UI\Form $form) {
        $data = $form->getValues();
        $session = $this->session->getSection("ingredients");
        if (!isset($session->title)) {
            $session->title = Nette\Utils\Strings::random(5);
        }
        $this->ingredients->add($data, $session->title);
        $this->flashMessage("Ingredience byla přidána.");
        $this->redrawControl("ingredients");
    }

}
