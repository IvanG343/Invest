<?php

namespace application\models;

use application\core\Model;

class Merchant extends Model {
	
	public function validatePerfectMoney($post, $tariff) {
		$params =
			$post['PAYMENT_ID'].':'.
			$post['PAYEE_ACCOUNT'].':'.
			$post['PAYMENT_AMOUNT'].':'.
			$post['PAYMENT_UNITS'].':'.
			$post['PAYMENT_BATCH_NUM'].':'.
			$post['PAYER_ACCOUNT'].':'.
			strtoupper(md5('secret')).':'.
			$post['TIMESTAMPGMT'];
		
		list($tid, $uid) = explode(',', $post['PAYMENT_ID']);
		$tid += 0;
		$uid += 0;
		$amount = $post['PAYMENT_AMOUNT'] + 0;
//		if (strtoupper(md5($params)) != $post['V2_HASH']) {
//			return false;
//		}
		if ($post['PAYMENT_UNITS'] != 'USD') {
			return false;
		}
		elseif (!isset($tariff[$tid])) {
			return false;
		}
		elseif ($amount > $tariff[$tid]['max'] or $amount < $tariff[$tid]['min']) {
			return false;
		}
		return [
			'tid' => $tid,
			'uid' => $uid,
			'amount' => $amount,
		];
	}
	
	public function createTariff($data) {
		debug($data);
	}

}