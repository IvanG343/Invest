<?php

namespace application\controllers;

use application\core\Controller;
use application\lib\Pagination;

class DashboardController extends Controller {
	
	public function investAction() {
		$vars = [
			'tariff' => $this->tariffs[$this->route['id']],
		];
		$this->view->render('Инвестировать', $vars);
	}
	
	public function tariffsAction() {
		$pagination = new Pagination($this->route, $this->model->tariffsCount());
		$vars = [
			"pagination" => $pagination->get(),
			"list" => $this->model->tariffsList($this->route),
		];
		$this->view->render('Инвестиции', $vars);
	}
	
	public function historyAction() {
		$pagination = new Pagination($this->route, $this->model->historyCount());
		$vars = [
			"pagination" => $pagination->get(),
			"list" => $this->model->historyList($this->route),
		];
		$this->view->render('История', $vars);
	}
	
	public function referralsAction() {
		if(!empty($_POST)) {
			if($_SESSION['account']['refBalance'] <= 0) {
				$this->view->message('error', 'Баланс пуст');
			}
			$this->model->createRefWithdraw();
			$this->view->message('success', 'Заявка на вывод принята');
		}
		$pagination = new Pagination($this->route, $this->model->referralsCount());
		$vars = [
			"pagination" => $pagination->get(),
			"list" => $this->model->referralsList($this->route),
		];
		$this->view->render('Рефералы', $vars);
	}
	

}