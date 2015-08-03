<?php

namespace App\Presenters;

use Nette,
    App\Model;

/**
 * Base presenter for all application presenters.
 */
abstract class BasePresenter extends Nette\Application\UI\Presenter {

    /**
     * @var Model\RecipesManager @inject
     */
    public $recipes;
    private $active = array("homepage" => "", "products" => "", "news" => "", "recipes" => "", "kontakt" => "","prodejny"=>"");


    /** @persistent */
    public $locale;

    /** @var \Kdyby\Translation\Translator @inject */
    public $translator;

    public function startup() {
        parent::startup();
        $this->template->title = "Dip'it";
    }

    public function setActive($presenterMame) {
        $this->active[$presenterMame] = "active";
    }

    public function setTitle($title) {
        $this->template->title = $title;
    }

    public function setKeyWords($words) {
        $this->template->words = $words; // ? crop
    }

    public function setHtmlDesc($htmlDescription) {
        $this->template->html = $this->crop($htmlDescription, 250);
    }

    public function afterRender() {
        parent::afterRender();
        $this->template->active = $this->active;
        $this->template->randomRecipe = $this->recipes->getRandom();
    }

    /**
     * Ze zadaného textu vezme x znaků (dle $limit) a následně testuje, jestli je xtý znak mezera,
     * pokud ne ořezává text, dokud není poslední znak právě mezera
     *
     * @param type $text
     * @param type $limit
     * @return string
     */
    public function crop($text, $limit = 200, $dots = false) {
        if($dots){
             $limit-=3;
        }
        
        $text = strip_tags($text);
        $text = html_entity_decode($text, ENT_NOQUOTES, 'UTF-8');
        if (strlen($text) > $limit) {
            $text = substr($text, 0, $limit + 1);
            $pos = strrpos($text, " ");
            $text = substr($text, 0, ($pos ? $pos : -1));
        }
        
        if($dots){
            $text.="...";
        }
        return $text;
    }

    /**
     * Nastavení překladače pro latte
     */
    protected final function createTemplate($class = NULL) {
        $template = parent::createTemplate($class);

        $this->translator->createTemplateHelpers()
            ->register($template->getLatte());

        return $template;
    }

    /**
     * Zkratka pro přeložení textu
     * @param String $message Název proměnné v <category>.<locale>.neon
     * @param String $category Začátek názvu souboru  <category>.<locale>.neon
     * Pokud nebude zadán veme se název presenteru
     * @param Array|String|Int $parameters Parametr/y pro zprávu
     *
     * @return String Hodnota proměnné v <category>.<locale>.neon
     */
    public final function trans($message, $category = null, $parameters = array()) {
        if (is_null($category)) {
            $category = strtolower($this->getPresenter()->name);
        }
        return $this->translator->translate('messages.'.$category . "." . $message, $parameters);
    }

}
