<?php
namespace CampaignBundle;

use Core\Controller;

class PageController extends Controller {

	public function indexAction() {
		
		// global $user;
		// echo file_get_contents('http://uat.coach.samesamechina.com/api/coach/create_tmp_qr/'.$user->openid.'?access_token='.TOKEN);

		exit;
	}

	public function imageAction() {
		
		$request = $this->request;
    	$fields = array(
			'id' => array('notnull', '120')
		);
		$request->validation($fields);
		$id = $request->query->get('id');
		$DatabaseAPI = new \Lib\DatabaseAPI();
		$user = $DatabaseAPI->findQrcodeByUid($id);
		if ($user->qrcode) {
			header('Content-type: image/jpg');
			echo file_get_contents($user->qrcode);
			exit;
		}
		$result = file_get_contents('http://uat.coach.samesamechina.com/api/coach/create_tmp_qr/'.$user->openid.'?access_token='.TOKEN);
		$result = json_decode($result);
		$DatabaseAPI->saveImage($user->uid, $result->img_src);
		header('Content-type: image/jpg');
		echo file_get_contents($result->img_src);;
		exit;
	}

	public function loginAction() {
		$userAPI = new \Lib\UserAPI();
		$user = $userAPI->userLogin('oqQW1wz0PN8xz5xzGqgv33BmerOU');
		echo 'Login!';
		exit;
	}

	public function testAction() {
		// $data = array("touser"=>"oqQW1w1pPzCMyWsiD45HPTHUvaPo",
		// 	"msgtype"=>"text",
		// 	"text"=>array("content"=>"test"));
		// $api_url = "http://uat.coach.samesamechina.com/v2/wx/message2/custom/text?access_token=".TOKEN;
	 //    $ch = curl_init();
	 //    // print_r($ch);
	 //    curl_setopt ($ch, CURLOPT_URL, $api_url);
	 //    //curl_setopt($ch, CURLOPT_POST, 1);
	 //    curl_setopt ($ch, CURLOPT_HEADER, 0);
	 //    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
	 //    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
	 //    $info = curl_exec($ch);
	 //    curl_close($ch);
	 //    var_dump($info);exit;
		$RedisAPI = new \Lib\RedisAPI();
		echo $uid = $RedisAPI->popSend();exit;
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

	public function flushredisAction() {
		$RedisAPI = new \Lib\RedisAPI();
		
		$rs= $RedisAPI ->flush();
		var_dump($rs);
		exit;
	}

	public function getsendAction() {
		$RedisAPI = new \Lib\RedisAPI();
		
		$rs= $RedisAPI ->getSend();
		var_dump($rs);
		echo '</br>';
		$RedisAPI->getKey();
		exit;
	}

	public function qrcodeAction() {
		$request = $this->request;
		$id = $request->query->get('id') ? $request->query->get('id') : 1;
		$this->render('qrcode',array('qrcode'=>"getimg?id=".$id));
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
				$RedisAPI = new \Lib\RedisAPI();
				if ($RedisAPI->getParent($user1->uid)) {
					//已绑定
					$response = array();
					$data = array('status' => 'success', 'data' => $response);
					$this->dataPrint($data);
				}
				//未绑定
				$user2 = $DatabaseAPI->findUserByOpenid($info->scene_str);
				
				$RedisAPI ->setParent($user1->uid, $user2->uid);
				$RedisAPI ->setSend($user1->uid);
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