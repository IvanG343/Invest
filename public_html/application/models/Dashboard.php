<?php

namespace application\models;

use application\core\Model;

class Dashboard extends Model {
	
	/* History methods */
	public function historyCount() {
		$params = [
			'uid' => $_SESSION['account']['id'],
		];
		return $this->db->column("SELECT COUNT(id) FROM history WHERE uid = :uid", $params);
	}
	
	public function historyList($route) {
		$max = 10;
		$params = [
			"max" => $max,
			"start" => (($route["page"] ?? 1) - 1) * $max,
			'uid' => $_SESSION['account']['id'],
		];
		return $this->db->row("SELECT * FROM history WHERE uid = :uid ORDER BY id DESC LIMIT :start, :max", $params);
	}
	
	/* Referral methods */
	public function referralsCount() {
		$params = [
			'uid' => $_SESSION['account']['id'],
		];
		return $this->db->column("SELECT COUNT(id) FROM accounts WHERE ref = :uid", $params);
	}
	
	public function referralsList($route) {
		$max = 10;
		$params = [
			"max" => $max,
			"start" => (($route["page"] ?? 1) - 1) * $max,
			'uid' => $_SESSION['account']['id'],
		];
		return $this->db->row("SELECT login, email FROM accounts WHERE ref = :uid ORDER BY id DESC LIMIT :start, :max", $params);
	}
	
	/* Tariffs methods */
	public function tariffsCount() {
		$params = [
			'uid' => $_SESSION['account']['id'],
		];
		return $this->db->column("SELECT COUNT(id) FROM tariffs WHERE uid = :uid", $params);
	}
	
	public function tariffsList($route) {
		$max = 10;
		$params = [
			"max" => $max,
			"start" => (($route["page"] ?? 1) - 1) * $max,
			'uid' => $_SESSION['account']['id'],
		];
		return $this->db->row("SELECT * FROM tariffs WHERE uid = :uid ORDER BY id DESC LIMIT :start, :max", $params);
	}
	
}