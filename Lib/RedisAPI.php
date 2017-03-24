<?php
namespace Lib;

class RedisAPI {

	private $_redis;

	public function __construct() {
		$redis = new \Redis();
   		$redis->connect(REDIS_HOST, REDIS_PORT);
   		$this->_redis = $redis;
	}

	public function retrieveReplyObject($received) {
		$key = $this->generateRedisKey($received);
		$replyObject = $this->replyTriggerQuery($key);
		if($replyObject) {
			$replyObject->fromUserName = $received->ToUserName;
	  		$replyObject->toUserName = $received->FromUserName;
	  		return $replyObject;
		} 
		return NULL;
	}

	public function getAccessToken() {
		return $this->getAccessKey();
	}

	public function getJSApiTicket() {
		return $this->getAccessKey('jsapi_ticket');
	}

	private function getAccessKey($type = 'access_token') {
		$key = WECHAT_TOKEN_PREFIX . $type;
  		if($key_value = $this->_redis->get($key)) {
    		return unserialize($key_value);
  		} else {
    		$key_value = '';
    		$expires_in = '';
    		if($type == 'access_token') {
				$wechatAPI = new WechatAPI(TOKEN, APPID, APPSECRET);
  				$data = $wechatAPI->getAccessToken();
  				if(isset($data->access_token)) {
  					$key_value = $data->access_token;
	        		$expires_in = $data->expires_in - AHEADTIME;
  				}
		    } else {
		      $wechatJSSDKAPI = new JSSDKAPI($this->getAccessKey('access_token'));
		      $data = $wechatJSSDKAPI->getTicket($type);
		      if($data->ticket){
		        $key_value = $data->ticket;
		        $expires_in = $data->expires_in - AHEADTIME;
		      } 
		    }
		    $this->_redis->set($key, serialize($key_value));
			$this->_redis->setTimeout($key, $expires_in);
			return $key_value;
  		}
	}

	public function get($key) {
		$key_value = $this->_redis->get($key);
		return unserialize($key_value);
	}

	public function jssdkConfig($url) {
		$RedisAPI = new \Lib\RedisAPI();
		$jsapi_ticket = $this->getJSApiTicket();
		$wechatJSSDKAPI = new \Lib\JSSDKAPI();
		return json_encode($wechatJSSDKAPI->getJSSDKConfig(APPID, $jsapi_ticket, $url));
	}

	public function setParent($child, $parent) {
		$this->_redis->set('parent:'. $child, $parent);
		return true;
	}

	public function getParent($child) {
		if ($this->_redis->get('parent:'. $child)) {
			return $this->_redis->get('parent:'. $child);
		}
		return null;
	}

	public function getAllParent($child) {
		$list = array();
		$list['parent'] = $this->getParent($child);
		$user = $list['parent'];
		$list['head'] = array();
		while ($user = $this->getParent($user)) {
			$list['head'][] = $user;
		}
		return $list;
	}

	public function setSend($uid) {
		if ($this->_redis->lSize('sendList') == 0) {
			$this->_redis->lPush('sendList', $uid);
		} else {
			$this->_redis->lPushx('sendList', $uid);
		}
		return $this->_redis->lSize('sendList');
	}

	public function getSend() {
		
		return $this->_redis->lSize('sendList');
	}

	public function getKey() {
		var_dump($this->_redis->keys('parent:*'));
		var_dump($this->_redis->lrange('sendList', 0, -1));
		exit;
	}

	public function popSend() {
		return $this->_redis->rPop('sendList');
	}

	public function flush() {
		$this->_redis->flushAll();
	}

	public function runScript() {
		$RedisAPI = new \Lib\RedisAPI();
		$uid = $RedisAPI->popSend();
		if (!$uid) {
			return 0;
		}
		//给上级加分
		$pid = $RedisAPI->getParent($uid);
		if (!$pid) {
			return 0;
		}
		if ($pid == 1) {
			return 0;
		}
		$DatabaseAPI = new \Lib\DatabaseAPI();
		$user = $DatabaseAPI->findQrcodeByUid($uid);
		$parent = $DatabaseAPI->findQrcodeByUid($pid);
		$CurioWechatAPI = new \Lib\CurioWechatAPI();
		$CurioWechatAPI->sendText($parent->openid, $user->nickname.'通过关注为您获取20积分');
		$DatabaseAPI->scorePlus($parent->uid, 20, 20);
		$DatabaseAPI->scoreLog($uid, $parent->uid, 20, '关注');
		//给上级的上级加分
		$return = 0;
		while ($pid = $RedisAPI->getParent($pid)) {
			$parents = $DatabaseAPI->findQrcodeByUid($pid);
			$CurioWechatAPI->sendText($parents->openid, $parent->nickname.'通过下级关注为您获取5积分');
			$DatabaseAPI->scorePlus($parents->uid, 5, 5);
			$DatabaseAPI->scoreLog($uid, $parents->uid, 5, '下级关注');
			$parent = $parents;
			$return++;
		}
		return $return;
	}

	public function runScriptUpdate() {
		$RedisAPI = new \Lib\RedisAPI();
		echo date('Y-m-d H:i:s') . "------------------------------------------\n";
		while ($uid = $this->_redis->rPop('sendList')) {
			echo "\nCurrent Child: {$uid}\n";
			$pid = $RedisAPI->getParent($uid);
			if($pid && $pid > 1) {
				$DatabaseAPI = new \Lib\DatabaseAPI();
				$user = $DatabaseAPI->findQrcodeByUid($uid);
				$parent = $DatabaseAPI->findQrcodeByUid($pid);
				$CurioWechatAPI = new \Lib\CurioWechatAPI();
				$CurioWechatAPI->sendText($parent->openid, $user->nickname.'通过关注为您获取20积分');
				$DatabaseAPI->scorePlus($parent->uid, 20, 20);
				$DatabaseAPI->scoreLog($uid, $parent->uid, 20, '关注');
				echo "Recipient Parents: {$user->nickname} : {$parent->openid}\n";
				//给上级的上级加分
				while ($pid = $RedisAPI->getParent($pid)) {
					$parents = $DatabaseAPI->findQrcodeByUid($pid);
					$CurioWechatAPI->sendText($parents->openid, $parent->nickname.'通过下级关注为您获取5积分');
					$DatabaseAPI->scorePlus($parents->uid, 5, 5);
					$DatabaseAPI->scoreLog($uid, $parents->uid, 5, '下级关注');
					$parent = $parents;
					echo "-- Deep Recipient Parents: {$parent->nickname} : {$parents->openid}\n";
				}
			}
		}
		echo date('Y-m-d H:i:s') . "------------------------------------------\n\n";
	}

}