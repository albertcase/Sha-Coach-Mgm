<?php
namespace WechatBundle;

use Core\Controller;

class WechatController extends Controller {

	public function callbackAction() {
		$request = $this->request;
		$fields = array(
			'redirect_uri' => array('notnull', '120'),
			'code' => array('notnull', '121'),
		);
		$request->validation($fields);
		$redirect_uri = $request->query->get('redirect_uri');
		$code = $request->query->get('code');
		$url = urldecode($redirect_uri);
		$wechatUserAPI = new \Lib\WechatAPI();

		$access_token = $wechatUserAPI->getSnsAccessToken($code, APPID, APPSECRET);

		if(isset($access_token->openid)) {
			$param = array();
			if($access_token->scope == 'snsapi_base') {
				$userAPI = new \Lib\UserAPI();
				$user = $userAPI->userLogin($access_token->openid);
				if(!$user) {
					$userAPI->userRegister($access_token->openid);
				}
			} 
			if($access_token->scope == 'snsapi_userinfo') {
				$param['openid'] = $access_token->openid;
				$param['access_token'] = $access_token->access_token;
			}
			$this->redirect($url);
		}
	}

	/**
	 * JSSDK JS
	 */
	public function jssdkConfigJsAction() {
		$request = $this->Request();
		$fields = array(
		    'url' => array('notnull', '120'),
	    );
		$request->validation($fields);
		$url = urldecode($request->query->get('url'));
	  	$this->hostValid($url);
	  	$config = $this->jssdkConfig($url);
	  	$json = json_encode(array('status' => '1', 'data' => $config));
	  	return $this->Response("SignWeiXinJs({$json})");
	}

	public function jssdkConfig($url = '') {
		$RedisAPI = new \Lib\RedisAPI();
		$jsapi_ticket = $RedisAPI->getJSApiTicket();
		$wechatJSSDKAPI = new \Lib\JSSDKAPI();
		return $wechatJSSDKAPI->getJSSDKConfig(APPID, $jsapi_ticket, $url);
	}

}
