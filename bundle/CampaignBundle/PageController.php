<?php
namespace CampaignBundle;

use Core\Controller;

class PageController extends Controller {

	public function indexAction() {
		
		global $user;
		echo file_get_contents('http://uat.coach.samesamechina.com/api/coach/create_tmp_qr/'.$user->openid.'?access_token='.TOKEN);
		exit;
	}

	public function testAction() {
		$data = array("touser"=>"oqQW1w1pPzCMyWsiD45HPTHUvaPo",
			"msgtype"=>"text",
			"text"=>array("content"=>"test"));
		$api_url = "http://uat.coach.samesamechina.com/v2/wx/message2/custom/text?access_token=".TOKEN;
	    $ch = curl_init();
	    // print_r($ch);
	    curl_setopt ($ch, CURLOPT_URL, $api_url);
	    //curl_setopt($ch, CURLOPT_POST, 1);
	    curl_setopt ($ch, CURLOPT_HEADER, 0);
	    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
	    $info = curl_exec($ch);
	    curl_close($ch);
	    var_dump($info);exit;
		/*
		$RedisAPI = new \Lib\RedisAPI();
		$RedisAPI ->setParent(2,1);
		$RedisAPI ->setParent(3,2);
		$RedisAPI ->setParent(4,3);
		$RedisAPI ->setParent(5,4);
		$RedisAPI ->setParent(6,5);
		$RedisAPI ->setParent(7,6);
		$rs= $RedisAPI ->getAllParent(5);
		var_dump($rs);
		echo 1;exit;
		*/
	}

	public function qrcodeAction() {
		$this->render('qrcode',array('qrcode'=>'http://uat.coach.samesamechina.com/sites/default/files/kuri_wechat/qr/02V3NN9BF5eR31Sqorho1g.png'));
	}

	public function replyAction() {
		$data = isset($GLOBALS['HTTP_RAW_POST_DATA']) ? $GLOBALS['HTTP_RAW_POST_DATA'] : $_GET['data'];
		if (!$data) {
			$data = array('status' => 'failed');
		    $this->dataPrint($data);
		}	
		$DatabaseAPI = new \Lib\DatabaseAPI();
		$info = json_decode($data);

		if($DatabaseAPI->insertReply($data, $info)) {
			if ($info->openid != $info->scene_str) {
				$user1 = $DatabaseAPI->findUserByOpenid($info->openid);
				if (!$user1) {
					$user1 = $DatabaseAPI->insertUserByQrcode($info->openid, $info->nickname, $info->headimgurl);
				}
				
				if ($DatabaseAPI->checkband($user1->uid)) {
					//已绑定
					$response = array();
					$data = array('status' => 'success', 'data' => $response);
					$this->dataPrint($data);
				}
				//未绑定
				$user2 = $DatabaseAPI->findUserByOpenid($info->scene_str);

				$DatabaseAPI->band($user1->uid, $user2->uid);
				$RedisAPI = new \Lib\RedisAPI();
				$RedisAPI ->setParent($user1->uid, $user2->uid);
				$response = array('openid' => $info->openid, 'text' => '<a href="'.BASE_URL.'qrcode?id='.$user1->uid.'">点击获取您的专属二维码</a>');
				$data = array('status' => 'success', 'data' => $response);
				$this->dataPrint($data);
			}
			$response = array();
			$data = array('status' => 'success', 'data' => $response);
			$this->dataPrint($data);
		} else {
			$response = array();
			$data = array('status' => 'success', 'data' => $response);
			$this->dataPrint($data);
		}


	}

	public function clearCookieAction() {
		setcookie('_user', json_encode($user), time(), '/');
		$this->statusPrint('success');
	}

	public function qrscanAction() {
		$data = isset($GLOBALS['HTTP_RAW_POST_DATA']) ? $GLOBALS['HTTP_RAW_POST_DATA'] : 1;	
		$DatabaseAPI = new \Lib\DatabaseAPI();
		$DatabaseAPI->insertLog($data);
		exit;
	}
}