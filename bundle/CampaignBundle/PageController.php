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
	}

	public function replyAction() {
		$data = isset($GLOBALS['HTTP_RAW_POST_DATA']) ? $GLOBALS['HTTP_RAW_POST_DATA'] : 1;	
		$DatabaseAPI = new \Lib\DatabaseAPI();
		$DatabaseAPI->insertLog($data);
		exit;
		// $request = $this->request;
  //   	$fields = array(
		// 	'openid' => array('notnull', '120'),
		// 	'nickname' => array('notnull', '121'),
		// 	'headimgurl' => array('notnull', '122'),
		// 	'scene_str' => array('notnull', '123'),
		// );
		// $request->validation($fields);
		
		// $DatabaseAPI = new \Lib\DatabaseAPI();
		// $data = new \stdClass();
		// $data->openid = $request->request->get('openid');
		// $data->nickname = $request->request->get('nickname');
		// $data->headimgurl = $request->request->get('headimgurl');
		// $data->scene_str = $request->request->get('scene_str');

		// if($DatabaseAPI->insertReply($data)) {
		// 	$data = array('status' => 'success');
		// 	$this->dataPrint($data);
		// } else {
		// 	$data = array('status' => 'success');
		// 	$this->dataPrint($data);
		// }
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