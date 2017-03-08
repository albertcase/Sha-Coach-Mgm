<?php

define("BASE_URL", 'http://coachmgmtest.samesamechina.com/');
define("TEMPLATE_ROOT", dirname(__FILE__) . '/../template');
define("VENDOR_ROOT", dirname(__FILE__) . '/../vendor');

//User
define("USER_STORAGE", 'COOKIE');

//Wechat Vendor
define("WECHAT_VENDOR", 'curio'); // default | curio

//Wechat config info
define("TOKEN", 'zcBpBLWyAFy6xs3e7HeMPL9zWrd7Xy');
define("APPID", '?????');
define("APPSECRET", '?????');
define("NOWTIME", date('Y-m-d H:i:s'));
define("AHEADTIME", '100');

define("NONCESTR", '1jvia1');
define("CURIO_AUTH_URL", 'http://uat.coach.samesamechina.com/api/wechat/oauth/auth/3f7a493c-1db6-41c3-8406-188ddf0a4ee8'); 

//Redis config info
define("REDIS_HOST", '127.0.0.1');
define("REDIS_PORT", '6379');

//Database config info
define("DBHOST", '127.0.0.1');
define("DBUSER", 'root');
define("DBPASS", '1qazxsw2');
define("DBNAME", 'coach_mgm');

//Wechat Authorize
define("CALLBACK", 'wechat/callback');
define("SCOPE", 'snsapi_base');

//Wechat Authorize Page
define("AUTHORIZE_URL", '[
	"/",
	"exchange"
]');

//Account Access
define("OAUTH_ACCESS", '{
	"xxxx": "samesamechina.com" 
}');
define("JSSDK_ACCESS", '{
	"xxxx": "samesamechina.com"
}');

define("ENCRYPT_KEY", '29FB77CB8E94B358');
define("ENCRYPT_IV", '6E4CAB2EAAF32E90');

define("WECHAT_TOKEN_PREFIX", 'wechat:token:');







