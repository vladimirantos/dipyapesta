<?php

namespace App\Presenters;

use Nette,
	App\Model;


/**
 * Sign in/out presenters.
 */
class SignPresenter extends BasePresenter
{

    /**
     * @var Model\UserManager @inject
     */
    public $userManager;

	/**
	 * Sign-in form factory.
	 * @return Nette\Application\UI\Form
	 */
	protected function createComponentSignInForm()
	{
        b(Nette\Security\Passwords::hash(12345));
        $form = new Nette\Application\UI\Form();
        $form->addText("email", "Email")->setRequired("Nezadal jsi email");
        $form->addPassword("password", "Heslo")->setRequired("Nezadal jsi heslo");
        $form->addSubmit("send", "Přihlásit se");
        $form->onSuccess[] = $this->signInFormSucceeded;
        return $form;
	}


	public function signInFormSucceeded($form, $values)
	{
        try{
            $this->getUser()->login($values->email, $values->password);
            $this->redirect("Admin:Homepage:");
        }catch (Nette\Security\AuthenticationException $e){
            $form->addError($e->getMessage());
        }
	}


	public function actionOut()
	{
		$this->getUser()->logout();
		$this->flashMessage('You have been signed out.');
		$this->redirect('in');
	}

}
