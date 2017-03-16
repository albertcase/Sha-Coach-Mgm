<?php
namespace CampaignBundle;

use Core\Controller;

class PageController extends Controller {

	public function indexAction() {
		
		$this->render('index');
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
		$image = file_get_contents($result->img_src);
		$filename = date('His') . rand(100,999) . '.png';
		$folder = 'upload/img/'.date("Ymd").'/';
		if(!is_dir($folder)){        	
			if(!mkdir($folder, 0777, true))	
			{	
				return 5;
			}
			chmod($folder,0777);
		}
		$handle = fopen($folder.$filename, 'w');
		fwrite($handle, $image);
		fclose($handle);
		$DatabaseAPI->saveImage($user->uid, $folder.$filename);
		header('Content-type: image/jpg');
		echo file_get_contents($folder.$filename);;
		exit;
	}

	public function loginAction() {
		$userAPI = new \Lib\UserAPI();
		$user = $userAPI->userLogin('oqQW1w1pPzCMyWsiD45HPTHUvaPo');
		echo 'Login!';
		exit;
	}

	public function testAction() {
		$rand = rand(100000,999999);
		$data = '{"openid":"'.$rand.'","nickname":"'.$rand.'","headimgurl":"'.$rand.'","scene_str":"oqQW1w-O0LFVBKgR0wuNIcRy6uBk"}'; 
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
				if ($user2) {
					$RedisAPI ->setParent($user1->uid, $user2->uid);
					$RedisAPI ->setSend($user1->uid);
				} else {
					$RedisAPI ->setParent($user1->uid, 1);
					//$RedisAPI ->setSend($user1->uid);
				}	
				$response = array('openid' => $info->openid, 'text' => '<a href="'.BASE_URL.'qrcode?id='.$user1->uid.'">点击获取您的专属二维码</a>');
				//$RedisAPI->runScript();
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

	public function exchangeAction() {
		$this->render('exchange');
	}

	public function replyAction() {
		//$data = isset($GLOBALS['HTTP_RAW_POST_DATA']) ? $GLOBALS['HTTP_RAW_POST_DATA'] : $_GET['data'];
		$data = file_get_contents('php://input'); 
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
				if ($user2) {
					$RedisAPI ->setParent($user1->uid, $user2->uid);
					$RedisAPI ->setSend($user1->uid);
				} else {
					$RedisAPI ->setParent($user1->uid, 1);
					//$RedisAPI ->setSend($user1->uid);
				}	
				$response = array('openid' => $info->openid, 'text' => '<a href="'.BASE_URL.'qrcode?id='.$user1->uid.'">点击获取您的专属二维码</a>');
				//$RedisAPI->runScript();
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
		global $user;
		setcookie('_user0316', json_encode($user), time(), '/');
		$this->statusPrint('success');
	}

	public function qrscanAction() {
		$data = isset($GLOBALS['HTTP_RAW_POST_DATA']) ? $GLOBALS['HTTP_RAW_POST_DATA'] : 1;	
		$DatabaseAPI = new \Lib\DatabaseAPI();
		$DatabaseAPI->insertLog($data);
		exit;
	}
}