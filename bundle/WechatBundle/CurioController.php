<?php
namespace WechatBundle;

use Core\Controller;

class CurioController extends Controller {

	public function callbackAction() {
		$request = $this->request;
		$fields = array(
			'openid' => array('notnull', '120'),
		);
		$request->validation($fields);
		$userAPI = new \Lib\UserAPI();
		$user = $userAPI->userLogin($request->query->get('openid'));
		if(!$user) {
			$userAPI->userRegister($request->query->get('openid'));
		}
		$url = '';
		$this->redirect($url);
	}

	public function receiveUserInfoAction() {
		$data = $GLOBALS['HTTP_RAW_POST_DATA'];	
		$data = json_decode($data);
		if($data->code = 200) {
			$DatabaseAPI = new \Lib\DatabaseAPI();
			$DatabaseAPI->updateUser($data->data);
		} else {
			$this->statusPrint('error');
		}
	}

}
