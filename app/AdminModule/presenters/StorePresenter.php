<?php
/**
 * Created by PhpStorm.
 * User: Vladimír
 * Date: 3. 6. 2015
 * Time: 15:52
 */

namespace App\AdminModule\Presenters;


use App\Model\StoreManager;
use Nette\Application\UI\Form;

class StorePresenter extends AdminPresenter {

    /**
     * @var StoreManager @inject
     */
    public $storeManager;

    public function startup() {
        parent::startup();
    }

    public function renderDefault(){
        $this->title = "Seznam prodejen";
        $this->template->stores = $this->storeManager->getAll();
    }

    public function renderEdit($id){
        $data = $this->storeManager->get($id);
        $this->template->name = $data->name;
        $this['addStoreForm']->setDefaults($data);
    }

    protected function createComponentAddStoreForm(){
        $form = new Form();
        $form->addText("name", "Název")->setRequired("Nezadal jsi název");
        $form->addText("address", "Adresa")->setRequired("Nezadal jsi adresu");
        $form->addText("city", "Město")->setRequired("Nezadal jsi město");
        $form->addText("zipCode", "PSČ")->setRequired("Nezadal jsi PSČ");
        $form->addSubmit("send", "Uložit");
        $form->onSuccess[] = $this->addStoreFormSucceeded;
        return $form;
    }

    public function addStoreFormSucceeded($form, $values){
        if(!isset($this->params['id'])){
            $this->storeManager->insert((array)$values);
            $this->flashMessage("Obchod byl úspěšně vytvořen");
        }else{
            $this->storeManager->update((array)$values, $this->params['id']);
            $this->flashMessage("Obchod byl úspěšně editován");
        }
        $this->redirect("this");
    }

    public function editStoreFormSucceeded($form, $values){

    }

    public function handleDelete($idStore){
        $this->storeManager->delete($idStore);
        $this->flashMessage("Obchod byl úspěšně vymazán");
        $this->redirect("Store:");
    }
}