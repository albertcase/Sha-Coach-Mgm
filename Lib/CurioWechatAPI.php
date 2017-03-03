<?php
namespace Lib;

use Core\Response;

class CurioWechatAPI {
	
	public function wechatAuthorize() {
    	$response = new Response();
    	$response->redirect(CURIO_AUTH_URL);  
  	}

  	public function getUserInfo($openid) {
	  	$api_url = "http://coach.samesamechina.com/v2/wx/users/no_cache/" . $openid . "?access_token=" . CURIO_TOKEN;
	    $ch = curl_init();
	    // print_r($ch);
	    curl_setopt ($ch, CURLOPT_URL, $api_url);
	    //curl_setopt($ch, CURLOPT_POST, 1);
	    curl_setopt ($ch, CURLOPT_HEADER, 0);
	    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
	    //curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
	    $info = curl_exec($ch);
	    curl_close($ch);
	    $rs = json_decode($info, true);
	    return $rs;
	}

	public function sendText($openid) {
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
	    
	    return $rs;
	}

	public function isSubscribed($openid) {
	    $info = $this->getUserInfo($openid);
	    if(isset($info['subscribe']) && $info['subscribe'] == 1)
	      return TRUE;
	    else
	      return FALSE;
	}
}