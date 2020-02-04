<?php

namespace application\controllers;

use application\core\Controller;

class AccountController extends Controller {
	
	public function loginAction() {
		$this->view->render('Вход');
	}
	
	public function registerAction() {
		if(!empty($_POST)) {
			if(!$this->model->validate(['email', 'login', 'wallet', 'password'], $_POST)) {
				$this->view->message('error', $this->model->error);
			} elseif(!$this->model->checkEmailExists($_POST['email'])) {
				$this->view->message('error', $this->model->error);
			} elseif(!$this->model->checkLoginExists($_POST['login'])) {
				$this->view->message('error', $this->model->error);
			}
			$this->model->register($_POST);
			$this->view->message('success', 'Регистрация завершена, подтвердите свой E-mail');
		}
		$this->view->render('Регистрация');
	}
	
	public function recoveryAction() {
		$this->view->render('Восстановсить пароль');
	}
	
	public function confirmAction() {
		if(!$this->model->checkTokenExists($this->route['token'])){
			$this->view->redirect('account/login');
		}
		$this->model->activate($this->route["token"]);
		$this->view->render('Регистрация завершена');
	}
	
}
