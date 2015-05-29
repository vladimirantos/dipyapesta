<?php

namespace App\Presenters;

use Nette,
    App\Model;

/**
 * Homepage presenter.
 */
class HomepagePresenter extends BasePresenter {

    public function startup(){
        parent::startup();
    }

    public function renderKontakt() {
        $this->setTitle("Kontakt");
        $this->setActive("kontakt");
    }

    protected function createComponentContactForm(){
        $form = new Nette\Application\UI\Form();
        $form->addText("name", "Jméno a příjmení")->setRequired("Nevyplnil jsi jméno");
        $form->addText("email", "Email")->addRule(Nette\Application\UI\Form::EMAIL, "Email má chybný formát")->setRequired("Nevyplnil jsi email");
        $form->addTextArea("text", "Váš dotaz")->setRequired("Nevyplnil jsi dotaz");
        $form->addSubmit("send", "Odeslat");
        $form->onSuccess[] = $this->contactFormSucceeded;
        return $form;
    }

    public function contactFormSucceeded($form, $value){
        $mail = new Nette\Mail\Message();
        $mail->setFrom($value->email)
            ->addTo(email)
            ->setSubject('Dotaz od '.$value->name)
            ->setHTMLBody("Zpráva od: ".$value->name."<br>Email: ".$value->email."<br>Zpráva: ".$value->text);

        $mailer = new Nette\Mail\SendmailMailer();
        $mailer->send($mail);
        $this->flashMessage("Zpráva byla úspěšně odeslána. Děkujeme.");
        $this->redirect("this");
    }

    public function renderDefault() {
        $this->setActive("homepage");
    }

}
