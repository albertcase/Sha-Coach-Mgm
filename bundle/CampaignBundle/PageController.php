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
		$data = isset($GLOBALS['HTTP_RAW_POST_DATA']) ? $GLOBALS['HTTP_RAW_POST_DATA'] : '{"openid":"oqQW1w6XC7Cd9ApryXfNsOkffrSw","nickname":"\u6211\u662f\u827e\u6d3e\u5fb7\ud83d\ude03","headimgurl":"http:\/\/wx.qlogo.cn\/mmopen\/j2lJiakBYwa3J0ib1sTBPoUH2YJFQTcI68ibica2U2ACB6JKRPicIdlHjTEBXPatIPqK0Qw9icqE7foolTSWLJFnDFbV74tiaV58BbK\/0","scene_str":"oqQW1w2hTaqyg5URFoBjSHgY47WU"}';
		if (!$data) {
			$data = array('status' => 'failed');
		    $this->dataPrint($data);
		}	
		$DatabaseAPI = new \Lib\DatabaseAPI();
		$info = json_decode($data);

		if($DatabaseAPI->insertReply($data, $info)) {
			$response = array('openid' => $data->openid, 'text' => '<a href="http://www.baidu.com">关注成功</a>');
			$data = array('status' => 'success', 'data' => $response);
			$this->dataPrint($data);
		} else {
			$response = array('openid' => $data->openid, 'text' => '<a href="http://www.baidu.com">关注成功</a>');
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