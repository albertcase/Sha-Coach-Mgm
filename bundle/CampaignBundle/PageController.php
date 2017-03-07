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
		$user = $userAPI->userLogin('oqQW1w1pPzCMyWsiD45HPTHUvaPo');
		echo 'Login!';
		exit;
	}

	public function testAction() {
		$RedisAPI = new \Lib\RedisAPI();
		$row = $RedisAPI->runScript();
		echo $row;exit;
		
		// $RedisAPI = new \Lib\RedisAPI();
		// $uid = $RedisAPI->popSend();
		// if (!$uid) {
		// 	echo 0;
		// 	exit;
		// }
		// //给上级加分
		// $pid = $RedisAPI->getParent($uid);
		// if (!$pid) {
		// 	echo 0;
		// 	exit;
		// }
		// if ($pid == 1) {
		// 	echo 0;
		// 	exit;
		// }
		// $DatabaseAPI = new \Lib\DatabaseAPI();
		// $user = $DatabaseAPI->findQrcodeByUid($uid);
		// $parent = $DatabaseAPI->findQrcodeByUid($pid);
		// $CurioWechatAPI = new \Lib\CurioWechatAPI();
		// $CurioWechatAPI->sendText($parent->openid, $user->nickname.'通过关注为您获取40积分');
		// $DatabaseAPI->scorePlus($parent->uid, 40);
		// $DatabaseAPI->scoreLog($uid, $parent->uid, 40, '关注');
		// //给上级的上级加分
		// while ($pid = $RedisAPI->getParent($pid)) {
		// 	$parents = $DatabaseAPI->findQrcodeByUid($pid);
		// 	$CurioWechatAPI->sendText($parents->openid, $parent->nickname.'通过下级关注为您获取20积分');
		// 	$DatabaseAPI->scorePlus($parents->uid, 20);
		// 	$DatabaseAPI->scoreLog($uid, $parents->uid, 20, '下级关注');
		// 	$parent = $parents;
		// }
		// exit;
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
				$RedisAPI->runScript();
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