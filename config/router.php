<?php

$routers = array();
$routers['/wechat/oauth2'] = array('WechatBundle\Wechat', 'oauth');
$routers['/wechat/callback'] = array('WechatBundle\Wechat', 'callback');
$routers['/wechat/curio/callback'] = array('WechatBundle\Curio', 'callback');
$routers['/wechat/curio/receive'] = array('WechatBundle\Curio', 'receiveUserInfo');
$routers['/wechat/ws/jssdk/config/webservice'] = array('WechatBundle\WebService', 'jssdkConfigWebService');
$routers['/wechat/ws/jssdk/config/js'] = array('WechatBundle\WebService', 'jssdkConfigJs');
$routers['/ajax/post'] = array('CampaignBundle\Api', 'form');
$routers['/'] = array('CampaignBundle\Page', 'index');
$routers['/clear'] = array('CampaignBundle\Page', 'clearCookie');
$routers['/test'] = array('CampaignBundle\Page', 'test');
$routers['/api/coach/qr_scan'] = array('CampaignBundle\Page', 'qrscan');
$routers['/mgm/qr/reply'] = array('CampaignBundle\Page', 'reply');
$routers['/qrcode'] = array('CampaignBundle\Page', 'qrcode');
$routers['/flush'] = array('CampaignBundle\Page', 'flushredis');
$routers['/getsend'] = array('CampaignBundle\Page', 'getsend');
$routers['/login'] = array('CampaignBundle\Page', 'login');
$routers['/api/islogin'] = array('CampaignBundle\Api', 'islogin');