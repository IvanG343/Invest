<?php


namespace application\controllers;

use application\core\Controller;
use application\lib\Pagination;

class AdminController extends Controller {
	
	public function __construct($route) {
		parent::__construct($route);
		$this->view->layout = "admin";
	}
	
	public function loginAction() {
		if(isset($_SESSION["admin"])) {
			$this->view->redirect("admin/withdraw");
		}
		if(!empty($_POST)) {
			if(!$this->model->loginValidate($_POST)) {
				$this->view->message("error", $this->model->error);
			}
			$_SESSION["admin"] = true;
			$this->view->location("admin/withdraw");
		}
		$this->view->render("Вход");
	}
	
	public function withdrawAction() {
		$this->view->render('Заказы на выод средств');
	}
	
	public function historyAction() {
		$pagination = new Pagination($this->route, $this->model->historyCount());
		$vars = [
			"pagination" => $pagination->get(),
			"list" => $this->model->historyList($this->route),
		];
		$this->view->render('История', $vars);
		
	}
	
	public function tariffsAction() {
		$this->view->render('Список инвестиций');
	}
	
	public function logoutAction() {
		unset($_SESSION["admin"]);
		$this->view->redirect("admin/login");
	}
	
}