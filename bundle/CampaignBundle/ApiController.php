<?php
namespace CampaignBundle;

use Core\Controller;


class ApiController extends Controller {

    public function __construct() {

    	global $user;

        parent::__construct();

        if(!$user->uid) {
	        $this->statusPrint('100', 'access deny!');
        } 
    }

    public function formAction() {

    	global $user;

    	$request = $this->request;
    	$fields = array(
			'name' => array('notnull', '120'),
			'cellphone' => array('cellphone', '121'),
			'address' => array('notnull', '122'),
		);
		$request->validation($fields);
		$DatabaseAPI = new \Lib\DatabaseAPI();
		$data = new \stdClass();
		$data->uid = $user->uid;
		$data->name = $request->request->get('name');
		$data->cellphone = $request->request->get('cellphone');
		$data->address = $request->request->get('address');

		if($DatabaseAPI->saveInfo($data)) {
			$data = array('status' => 1);
			$this->dataPrint($data);
		} else {
			$this->statusPrint('0', 'failed');
		}
    }

    public function isloginAction() {

    	global $user;
    	$DatabaseAPI = new \Lib\DatabaseAPI();
    	$info = $DatabaseAPI->findInfoByUid($user->uid);
		$oauth = $DatabaseAPI->findUserByOpenid($user->openid);
		$data = array('status' => 1, 'msg'=> $oauth, 'info' => $info);
		$this->dataPrint($data);
    	
    }

    public function checkAction() {
    	global $user;
    	$DatabaseAPI = new \Lib\DatabaseAPI();
    	$count = $DatabaseAPI->checkGift($user->uid);
    	if ($count>=2) {
    		$data = array('status' => 0);
			$this->dataPrint($data);
    	} else {
    		$data = array('status' => 1);
			$this->dataPrint($data);
    	}
    	
    }

    public function prizelistAction() {
        $DatabaseAPI = new \Lib\DatabaseAPI();
        $list = $DatabaseAPI->prizeList();
        $data = array('status' => 1, 'msg' => $list);
        $this->dataPrint($data);
        
    }

    public function exchangeAction() {

        global $user;

        $request = $this->request;
        $fields = array(
            'id' => array('notnull', '120'),
        );
        $request->validation($fields);
        $id = $request->request->get('id');

        $DatabaseAPI = new \Lib\DatabaseAPI();
        $score = $DatabaseAPI->getScore($user->uid);

        $count = $DatabaseAPI->checkGift($user->uid);
        if ($count>=2) {
            $data = array('status' => 3, 'msg' => '已经兑换过两份礼品');
            $this->dataPrint($data);
        }

        $prize = $DatabaseAPI->getPrizeById($id);
        if (!$prize) {
            $data = array('status' => 5, 'msg' => '非法提交');
            $this->dataPrint($data);
        }
        if ($prize->quota<=0) {
            $data = array('status' => 4, 'msg' => '库存不足');
            $this->dataPrint($data);
        }
        if ($prize->score > $score) {
            $data = array('status' => 2, 'msg' => '积分不足');
            $this->dataPrint($data);
        }

        $DatabaseAPI->exchange($user->uid, $id, $prize->name, $prize->score);
        $DatabaseAPI->minusQuota($id);
        $DatabaseAPI->scorePlus($user->uid, -$prize->score, 0);
        $DatabaseAPI->scoreLog(0, $user->uid, -$prize->score, '积分兑换');
        $data = array('status' => 1, 'msg' => '兑换成功');
        $this->dataPrint($data);
        
    }

    public function cardAction() {
        global $user;

        $wechatapi = new \Lib\WechatAPI();
        $list = $wechatapi->cardList('pqQW1w_KgUbDEskJYuak55VeV21g');
        return $this->statusPrint(1, $list);
    }

}
