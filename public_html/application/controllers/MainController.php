<?php

namespace application\controllers;

use application\core\Controller;
use application\lib\Pagination;
use application\models\Admin;

class MainController extends Controller {
	
	public function indexAction() {
		$pagination = new Pagination($this->route, $this->model->postCount());
		$vars = [
			"pagination" => $pagination->get(),
			"list" => $this->model->postList($this->route),
		];
		$this->view->render("Главная", $vars);
	}

	public function aboutAction() {
		$this->view->render("Обо мне");
	}

	public function contactAction() {
		if(!empty($_POST)) {
			if(!$this->model->contactValidate($_POST)) {
				$this->view->message("error", $this->model->error);
			}
			mail("childrenofbodom@mail.ru", "Сообщение из блога", $_POST["name"]."\n".$_POST["email"]."\n".$_POST["text"]);
			$this->view->message("success", "Сообщение отправлено");
		}
		$this->view->render("Контакты");
	}

	public function postAction() {
		$adminModel = new Admin;
		if(!$adminModel->isPostExists($this->route["id"])) {
			$this->view->errorCode(403);
		}
		$vars = [
			"data" => $adminModel->postData($this->route["id"])[0],
		];
		$this->view->render("Пост", $vars);
	}

}