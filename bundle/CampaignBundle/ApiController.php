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

}
