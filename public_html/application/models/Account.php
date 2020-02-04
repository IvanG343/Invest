<?php

namespace application\models;

use application\core\Model;

class Account extends Model {
	
	public function validate($input, $post) {
		
		$rules = [
			'email' => [
				'pattern' => '#^([a-z0-9_.-]{1,20}+)@([a-z0-9_.-]+)\.([a-z\.]{2,10})$#',
				'message' => 'E-mail адрес указан неверно',
			],
			'login' => [
				'pattern' => '#^[a-z0-9]{3,15}$#',
				'message' => 'Логин указан неверно (разрешены только латинские буквы и цифры от 3 до 15 символов',
			],
			'ref' => [
				'pattern' => '#^[a-z0-9]{3,15}$#',
				'message' => 'Логин пригласившего указан неверно',
			],
			'wallet' => [
				'pattern' => '#^[A-Za-z0-9]{3,15}$#',
				'message' => 'Кошелек Perfect Money указан неверно',
			],
			'password' => [
				'pattern' => '#^[A-Za-z0-9]{3,15}$#',
				'message' => 'Пароль указан неверно (разрешены только латинские буквы и цифры от 10 до 30 символов',
			
			],
		];
		foreach($input as $val) {
			if(!isset($post[$val]) || !preg_match($rules[$val]['pattern'], $post[$val])) {
				$this->error = $rules[$val]['message'];
				return false;
			}
		}
		return true;
	}
	
	public function checkEmailExists($email) {
		$params = [
			'email' => $email,
		];
		if($this->db->column('SELECT id FROM accounts WHERE email = :email', $params)) {
			$this->error = 'Этот E-mail уже используется';
			return false;
		}
		return true;
	}
	
	public function checkLoginExists($login) {
		$params = [
			'login' => $login,
		];
		if($this->db->column('SELECT login FROM accounts WHERE login = :login', $params)) {
			$this->error = 'Этот логин уже используется';
			return false;
		}
		return true;
	}
	
	public function checkTokenExists($token) {
		$params = [
			'token' => $token,
		];
		return $this->db->column('SELECT id FROM accounts WHERE token = :token', $params);
	}
	
	public function activate($token) {
		$params = [
			'token' => $token,
		];
		$this->db->query('UPDATE accounts SET status = 1, token = "" WHERE token = :token', $params);
	}
	
	public function createToken() {
		return substr(str_shuffle(str_repeat('0123456789abcdefghijklmnopqrstuvwxyz', 30)), 0, 30);
	}
	
	public function register($post) {
		$token = $this->createToken();
		$params = [
			'id' => '',
			'email' => $post['email'],
			'login' => $post['login'],
			'wallet' => $post['wallet'],
			'password' => password_hash($post['password'], PASSWORD_BCRYPT),
			'ref' => 0,
			'token' => $token,
			'status' => 0,
		];
		
		$this->db->query('INSERT INTO accounts VALUES (:id, :email, :login, :wallet, :password, :ref, :token, :status)', $params);
		mail($post['email'], 'Registration', 'Confirm: http://invest.hthere.ru/account/confirm/'.$token);
	}
	
}