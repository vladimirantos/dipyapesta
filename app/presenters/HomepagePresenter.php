<?php
namespace App\Presenters;

use Nette,
    App\Model;

/**
 * Homepage presenter.
 */
class HomepagePresenter extends BasePresenter {

    /**
     * @var Model\StoreManager @inject
     */
    public $storeManager;

    public function startup(){
        parent::startup();
        b($this->locale);
    }

    public function renderKontakt() {
        $this->setTitle($this->trans('title', 'contact'));
        $this->setActive("kontakt");
        $this->template->stores = $this->storeManager->getAll();
    }

    protected function createComponentContactForm(){
        $form = new Nette\Application\UI\Form();
        $form->setTranslator($this->translator);
        $form->addText("name", 'messages.contact.form.name')->setRequired("messages.contact.form.nameRequired");
        $form->addText("email", "messages.contact.form.email")->addRule(Nette\Application\UI\Form::EMAIL, "messages.contact.form.emailFormatError")->setRequired("messages.contact.form.emailRequired");
        $form->addTextArea("text", "messages.contact.form.text")->setRequired("messages.contact.form.textRequired");
        $form->addSubmit("send", "messages.contact.form.button");
        $form->onSuccess[] = $this->contactFormSucceeded;
        return $form;
    }

    public function contactFormSucceeded($form, $value){
        $mail = new Nette\Mail\Message();
        $mail->setFrom($value->email)
            ->addTo(email)
            ->setSubject($this->trans('email.subject', 'contact').$value->name)
            ->setHTMLBody($this->trans('email.text1', 'contact').$value->name."<br>".$this->trans('email.text2', 'contact').$value->email."<br>".$this->trans('email.text3', 'contact').$value->text);

        $mailer = new Nette\Mail\SendmailMailer();
        $mailer->send($mail);
        $this->flashMessage($this->trans('email.success', 'contact'));
        $this->redirect("this");
    }

    public function renderDefault() {
        $this->setActive("homepage");
    }

}
