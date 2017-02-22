<?php
namespace ListenerBundle;

use Core\Request;

class RequestListener {
	public function afterRequest($request) {
		$this->initUser();
		$this->authorize();
	}

	private function initUser() {
		global $user;
		$UserAPI = new \Lib\UserAPI();
		$user = $UserAPI->userLoad();
	}

	private function authorize() {
		global $user;
		$authorize_url = json_decode(AUTHORIZE_URL);
        if(!$user->uid) {
        	$request = new Request();
        	$current_router = $request->getRouter();
        	if(in_array($current_router, $authorize_url)) {
        		$current_url = $request->getUrl(TRUE);
        		$function_name = WECHAT_VENDOR . 'WechatAuthoize';
				call_user_func_array(array($this, $function_name), array($request, $current_url));		
        	}
        }
	}

	private function defaultWechatAuthoize($request, $current_url) {
	    $wechatUserAPI = new \Lib\UserAPI();
	    $wechatUserAPI->oauthAction(SCOPE, $current_url);   
	}

	private function curioWechatAuthoize($request, $current_url) {
		$request->setSourceUrl($current_url);
		$WechatAPI = new \Lib\CurioWechatAPI();
		$WechatAPI->wechatAuthorize();
	}
}