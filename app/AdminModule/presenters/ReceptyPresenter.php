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

    public function __construct() {
        parent::__construct();
        $this->setActive("recepty");
    }

    public function renderDefault() {
        $this->template->recipes = $this->recipes->getAll();
    }

    public function renderDetail($id, $language) {
        $this->template->recipe = $this->recipes->get($id, $language);
        $this->template->ingredients = $this->ingredients->getAll($id, $language);
    }

    public function renderNew() {
        $session = $this->session->getSection("recipe");
        if (isset($session->id_recipe))
            $this->template->ingredients = $this->ingredients->getAll($session->id_recipe, $session->language);
        else
            $this->template->ingredients = array();
    }

    public function renderEdit($id, $language) {
        $data = $this->recipes->get($id, $language);
        $this->template->product = $data;
        $this['createNew']->setDefaults($data);
        $this['main']->setDefaults($data);
        $session = $this->session->getSection("recipe");
        $session->id_recipe= $data->id_recipe;
        $session->language= $data->language;
        $this->template->ingredients = $this->ingredients->getAll($data->id_recipe,$data->language);
    }

    public function handleDelete($id, $language) {
        $this->recipes->delete($id, $language);
        $this->flashMessage("Recept byl úspěšně odstraněn", "success");
        $this->redirect("Recepty:");
    }

    public function handleDeleteIngredient($id) {
        $this->ingredients->deleteOne($id);
        $this->flashMessage("Ingredience byla úspěšně odstraněna", "success");
        $this->redrawControl("ingredients");
    }

    protected function createComponentMain() {
        $form = new UI\Form();
        $form->addSelect("translate", "Překlad článku: ", $this->recipes->getAllNewsPair())->setPrompt("Vyberte recept který chcete překládat");
        $form->addSelect("language", "Jazyk:", Model\Languages::toArray());
        $form->onSubmit[] = $this->mainInfo;
        return $form;
    }

    protected function createComponentCreateNew() {
        $form = new UI\Form();
        $form->addText("title", "Název:")
                ->setRequired("Zadejte prosím název receptu");

        $form->addTextArea("description", "Popis:")
                ->setRequired("Popiště prosím recept");

        $form->addHidden("action");
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
        $action = $data->action;
        unset($data->action);
        try {
            $session = $this->session->getSection("recipe");
            if (isset($session->id_recipe)) {
                $data->language = $session->language;
                $data->id_recipe = $session->id_recipe;
                $this->recipes->update($data);
            } else {
                $session->language = "cs";
                $data->language = $session->language;
                $session->id_recipe = $this->recipes->add($data);
            }
            if ($action == "done") {
                $this->flashMessage("Recept byl úspěšně vytvořen", "success");
                $this->redirect("Recepty:detail", $data->id_recipe,$data->language);
            } elseif ($action == "save") {
                $this->flashMessage("Recept byl úspěšně uložen", "success");
                $this->redrawControl("messages");
            }
        } catch (Nette\InvalidArgumentException $e) {
            $this->flashMessage($e->getMessage(), "error");
            $this->redrawControl("messages");
        }
    }

    public function newIngredient(UI\Form $form) {
        $data = $form->getValues();
        try {
            $session = $this->session->getSection("recipe");
            if (!isset($session->id_recipe)) {
                $session->id_recipe = $this->recipes->createId("recipes", "id_recipe");
                $session->language = "cs";
                $this->recipes->createEmpty($session->id_recipe, $session->language);
            }
            if (isset($session->language)) {
                $data->language = $session->language;
            }
            $this->ingredients->add($data, $session->id_recipe);
            $this->flashMessage("Ingredience byla přidána.", "success");
            $this->redrawControl("ingredients");
        } catch (Nette\InvalidArgumentException $e) {
            $this->flashMessage($e->getMessage(), "error");
            $this->redrawControl("messages");
        }
    }

    /**
     * Tato metoda ukládá informace do session (jazyk, id překládaného receptu)
     * @param \Nette\Application\UI\Form $form     
     */
    public function mainInfo(UI\Form $form) {
        $data = $form->getValues();
        $session = $this->session->getSection("recipe");
        if (!is_null($data->translate)) {
            $session->id_recipe = $data->translate;
        }else{
            $session->id_recipe = $this->recipes->createId("recipes", "id_recipe");
        }
        $session->language = $data->language;
        $this->recipes->createEmpty($session->id_recipe, $session->language);
    }

}
