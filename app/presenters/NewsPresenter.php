<?php

namespace App\Presenters;

use Nette,
    App\Model;

/**
 * Description of NewsPresenter
 *
 * @author Bruno Puzjak
 */
class NewsPresenter extends BasePresenter {

    /**
     * @var Model\NewsManager @inject
     */
    public $news;

    public function __construct() {
        parent::__construct();
        $this->setActive("news");
    }

    public function startup() {
        parent::startup();
    }

    public function renderDefault() {
        $this->setTitle($this->trans('title', 'news'));
        $news = $this->news->getAllByLang($this->locale);
        $this->template->news = $news;
        $words = "";
        $html = "";
        $wasWords = false;
        $wasHtml = false;
        foreach ($news as $key => $new) {
            if ($wasWords && !empty($new->words)) {
                $words .=', ';
            }
            if (!empty($new->words)) {
                $words .=$new->words;
                $wasWords = true;
            }


            if ($wasHtml && !empty($new->html)) {
                $html .=', ';
            }
            if (!empty($new->html)) {
                $html .=$new->html;
                $wasHtml = true;
            }
        }
        $this->setHtmlDesc($html);
        $this->setKeyWords($words);
    }

}
