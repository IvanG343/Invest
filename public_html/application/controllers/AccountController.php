<?php

namespace application\controllers;

use application\core\Controller;

class AccountController extends Controller {
	
	/* Регистрация */
	public function registerAction() {
		if(!empty($_POST)) {
			if(!$this->model->validate(['email', 'login', 'wallet', 'password', 'ref'], $_POST)) {
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
	
	public function confirmAction() {
		if(!$this->model->checkTokenExists($this->route['token'])){
			$this->view->redirect('account/login');
		}
		$this->model->activate($this->route["token"]);
		$this->view->render('Регистрация завершена');
	}
	
	/* Вход */
	public function loginAction() {
		if(!empty($_POST)) {
			if(!$this->model->validate(['login','password'], $_POST)) {
				$this->view->message('error', $this->model->error);
			}
			elseif(!$this->model->checkData($_POST['login'], $_POST['password'])) {
				$this->view->message('error', 'Логин или пароль указан неверно');
			}
			elseif(!$this->model->checkStatus('login', $_POST['login'])) {
				$this->view->message('error', $this->model->error);
			}
			$this->model->login($_POST['login']);
			$this->view->location('account/profile');
		}
		$this->view->render('Вход');
	}
	
	
	/* Профиль */
	public function profileAction() {
		$this->view->render('Профиль');
	}
	
	public function recoveryAction() {
		$this->view->render('Восстановсить пароль');
	}
	
}
