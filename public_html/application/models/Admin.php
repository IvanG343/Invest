<?php

namespace application\models;

use application\core\Model;

class Admin extends Model {
	
	public function loginValidate($post) {
		$config = require "application/config/admin.php";
		if($config["login"] != $post["login"] or $config["password"] != $post["password"]) {
			$this->error = "Логин или пароль указан неверно";
			return false;
		}
		return true;
	}
	
	public function historyCount() {
		return $this->db->column("SELECT COUNT(id) FROM history");
	}
	
	public function historyList($route) {
		$max = 10;
		$params = [
			"max" => $max,
			"start" => (($route["page"] ?? 1) - 1) * $max,
		];
		$arr = array();
		$result =  $this->db->row('SELECT * FROM history ORDER BY id DESC LIMIT :start, :max', $params);
		if(!empty($result)) {
			foreach($result as $key => $val) {
				$arr[$key] = $val;
				$params = [
					'id' => $val['uid'],
				];
				$account =  $this->db->row('SELECT login, email FROM accounts WHERE id = :id', $params)[0];
				$arr[$key]['login'] = $account['login'];
				$arr[$key]['email'] = $account['email'];
			}
		}
		return $arr;
	}
	
}