<?php
require_once "../Core/bootstrap.php";
include_once "../config/config.php";
include_once "../config/router.php";

$RedisAPI = new \Lib\RedisAPI();
print $RedisAPI->runScript();





