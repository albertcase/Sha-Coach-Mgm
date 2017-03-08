<?php
namespace Lib;
/**
 * DatabaseAPI class
 */
class DatabaseAPI {

	private $db;

	private function connect() {
		$connect = new \mysqli(DBHOST, DBUSER, DBPASS, DBNAME);
		$this->db = $connect;
		$this->db->query('SET NAMES UTF8');
		return $this->db;
	}
	/**
	 * Create user in database
	 */
	public function insertUser($userinfo){
		$nowtime = NOWTIME;
		$sql = "INSERT INTO `user` SET `openid` = ?, `created` = ?, `updated` = ?"; 
		$res = $this->connect()->prepare($sql); 
		$res->bind_param("sss", $userinfo->openid, $nowtime, $nowtime);
		if($res->execute()) 
			return $this->findUserByOpenid($userinfo->openid);
		else 
			return FALSE;
	}

	public function updateUser($data) {
		if ($this->findUserByOauth($data->openid)) {
			return TRUE;
		}
		$sql = "INSERT INTO `oauth` SET `openid` = ?, nickname = ?, headimgurl = ?";
		$res = $this->db->prepare($sql); 
		$res->bind_param("sss", $data->openid, $data->nickname, $data->headimgurl);
		if ($res->execute()) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	public function findUserByOauth($openid) {
		$sql = "SELECT id  FROM `oauth` WHERE `openid` = ?"; 
		$res = $this->db->prepare($sql);
		$res->bind_param("s", $openid);
		$res->execute();
		$res->bind_result($uid);
		if($res->fetch()) {
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * Create user in database
	 */
	public function findUserByOpenid($openid){
		$sql = "SELECT `uid`, `openid`, `nickname`, `headimgurl`, `score`, `maxscore` FROM `user` WHERE `openid` = ?"; 
		$res = $this->connect()->prepare($sql);
		$res->bind_param("s", $openid);
		$res->execute();
		$res->bind_result($uid, $openid, $nickname, $headimgurl, $score, $maxscore);
		if($res->fetch()) {
			$user = new \stdClass();
			$user->uid = $uid;
			$user->openid = $openid;
			$user->nickname = $nickname;
			$user->headimgurl = $headimgurl;
			$user->score = $score;
			$user->maxscore = $maxscore;
			return $user;
		}
		return NULL;
	}

	public function findQrcodeByUid($uid){
		$sql = "SELECT `openid`, `nickname`, `headimgurl`, `qrcode` FROM `user` WHERE `uid` = ?"; 
		$res = $this->connect()->prepare($sql);
		$res->bind_param("s", $uid);
		$res->execute();
		$res->bind_result($openid, $nickname, $headimgurl, $qrcode);
		if($res->fetch()) {
			$user = new \stdClass();
			$user->uid = $uid;
			$user->openid = $openid;
			$user->nickname = $nickname;
			$user->headimgurl = $headimgurl;
			$user->qrcode = $qrcode;
			return $user;
		}
		return NULL;
	}

	public function saveImage($uid, $qrcode) {
		$nowtime = NOWTIME;
		$sql = "UPDATE `user` SET `qrcode` = ?, `updated` = ? WHERE `uid` = ?"; 
		$res = $this->connect()->prepare($sql); 
		$res->bind_param("sss", $qrcode, $nowtime, $uid);
		if($res->execute()) 
			return TRUE;
		else 
			return FALSE;
	}

	/**
	 * 
	 */
	public function saveInfo($data){
		if($this->findInfoByUid($data->uid)) {
			$this->updateInfo($data);
		} else {
			$this->insertInfo($data);
		}
		return TRUE;
	} 

	/**
	 * 
	 */
	public function insertInfo($data){
		$nowtime = NOWTIME;
		$sql = "INSERT INTO `info` SET `uid` = ?, `name` = ?, `cellphone` = ?, `address` = ?, `created` = ?, `updated` = ?"; 
		$res = $this->connect()->prepare($sql); 
		$res->bind_param("ssssss", $data->uid, $data->name, $data->cellphone, $data->address, $nowtime, $nowtime);
		if($res->execute()) 
			return $res->insert_id;
		else 
			return FALSE;
	}

	/**
	 * 
	 */
	public function updateInfo($data){
		$nowtime = NOWTIME;
		$sql = "UPDATE `info` SET `name` = ?, `cellphone` = ?, `address` = ?, `updated` = ? WHERE `uid` = ?"; 
		$res = $this->connect()->prepare($sql); 
		$res->bind_param("sssss", $data->name, $data->cellphone, $data->address, $nowtime, $data->uid);
		if($res->execute()) 
			return $this->findInfoByUid($data->uid);
		else 
			return FALSE;
	}

	/**
	 * Create user in database
	 */
	public function findInfoByUid($uid){
		$sql = "SELECT `id`, `name`, `cellphone`, `address` FROM `info` WHERE `uid` = ?"; 
		$res = $this->connect()->prepare($sql);
		$res->bind_param("s", $uid);
		$res->execute();
		$res->bind_result($id, $name, $cellphone, $address);
		if($res->fetch()) {
			$info = new \stdClass();
			$info->id = $id;
			$info->name = $name;
			$info->cellphone = $cellphone;
			$info->address = $address;
			return $info;
		}
		return NULL;
	}

	public function insertReply($data, $info) {
		$nowtime = NOWTIME;
		$sql = "INSERT INTO `reply` SET `data` = '".$data."', `openid` = '".$info->openid."', `nickname` = '".$info->nickname."', `headimgurl` = '".$info->headimgurl."', `scene_str` = '".$info->scene_str."'"; 
		$res = $this->connect()->prepare($sql); 
		//$res->bind_param("ssss", $data->openid, $data->nickname, $data->headimgurl, $data->scene_str);
		if($res->execute()) 
			return $res->insert_id;
		else 
			return FALSE;
	}

	public function insertLog($data) {
		$nowtime = NOWTIME;
		$sql = "INSERT INTO `log` SET `data` = ?"; 
		$res = $this->connect()->prepare($sql); 
		$res->bind_param("s", $data);
		if($res->execute()) 
			return $res->insert_id;
		else 
			return FALSE;
	}

	public function checkband($uid) {
		$sql = "SELECT `id`, `pid` FROM `parent` WHERE `uid` = ?"; 
		$res = $this->connect()->prepare($sql);
		$res->bind_param("s", $uid);
		$res->execute();
		$res->bind_result($id, $pid);
		if($res->fetch()) {
			$info = new \stdClass();
			$info->id = $id;
			$info->pid = $pid;
			return $info;
		}
		return NULL;
	}

	public function band($uid, $pid) {
		$sql = "INSERT INTO `parent` set `uid` = ?, `pid` = ?"; 
		$res = $this->connect()->prepare($sql);
		$res->bind_param("ss", $uid, $pid);
		if($res->execute()) 
			return $res->insert_id;
		else 
			return FALSE;
	}

	/**
	 * Create user in database
	 */
	public function insertUserByQrcode($openid, $nickname, $headimgurl){
		$nowtime = NOWTIME;
		$sql = "INSERT INTO `user` SET `openid` = '".$openid."', `nickname` = '".$nickname."', `headimgurl` = '".$headimgurl."', `created` = ?, `updated` = ?"; 
		$res = $this->connect()->prepare($sql); 
		$res->bind_param("ss", $nowtime, $nowtime);
		if($res->execute()) 
			return $this->findUserByOpenid($openid);
		else 
			return FALSE;
	}

	public function scorePlus($uid, $score, $max = 0) {
		$sql = "UPDATE `user` SET `score` = `score` + ?, `maxscore` = `maxscore` + ? where uid= ?"; 
		$res = $this->connect()->prepare($sql); 
		$res->bind_param("sss", $score,  $max, $uid);
		if($res->execute()) 
			return TRUE;
		else 
			return FALSE;
	}

	public function scoreLog($uid, $pid, $score, $type){
		$nowtime = NOWTIME;
		$sql = "INSERT INTO `score` SET `uid` = ?, `rid` = ?, `score` = ?, `type` = ?"; 
		$res = $this->connect()->prepare($sql); 
		$res->bind_param("ssss", $pid, $uid, $score, $type);
		if($res->execute()) 
			return TRUE;
		else 
			return FALSE;
	}

	public function checkGift($uid) {
		$sql = "SELECT count(`id`) as num FROM `exchange` WHERE `uid` = ?"; 
		$res = $this->connect()->prepare($sql);
		$res->bind_param("s", $uid);
		$res->execute();
		$res->bind_result($num);
		if($res->fetch()) {
			return $num;
		}
		return 0;
	}

	public function getScore($uid) {
		$sql = "SELECT score  FROM `user` WHERE `uid` = ?"; 
		$res = $this->connect()->prepare($sql);
		$res->bind_param("s", $uid);
		$res->execute();
		$res->bind_result($score);
		if($res->fetch()) {
			return $score;
		}
		return 0;
	}

	public function getPrizeById($id) {
		$sql = "SELECT `id`, `name`, `image`, `quota`, `score` FROM `prize` WHERE `id` = ?"; 
		$res = $this->connect()->prepare($sql);
		$res->bind_param("s", $id);
		$res->execute();
		$res->bind_result($id, $name, $image, $quota, $score);
		if($res->fetch()) {
			$info = new \stdClass();
			$info->id = $id;
			$info->name = $name;
			$info->image = $image;
			$info->quota = $quota;
			$info->score = $score;
			return $info;
		}
		return 0;
	}

	public function prizeList() {
		$sql="SELECT `id`, `name`, `image`, `quota`, `score` FROM `prize`";
		$res = $this->connect()->query($sql);
		$data = array();
		while($rows = $res->fetch_array(MYSQLI_ASSOC))
		{
			$data[] = $rows;
		}		
		return $data;
	}

	public function exchange($uid, $pid, $pname, $score){
		$nowtime = NOWTIME;
		$sql = "INSERT INTO `exchange` SET `uid` = ?, `prize` = ?, `prizename` = ?, `score` = ?"; 
		$res = $this->connect()->prepare($sql); 
		$res->bind_param("ssss", $uid, $pid, $pname, $score);
		if($res->execute()) 
			return TRUE;
		else 
			return FALSE;
	}

	public function minusQuota($id) {
		$sql = "UPDATE `prize` SET `quota` = `quota` -1 where id= ?"; 
		$res = $this->connect()->prepare($sql); 
		$res->bind_param("s", $id);
		if($res->execute()) 
			return TRUE;
		else 
			return FALSE;
	}

}
