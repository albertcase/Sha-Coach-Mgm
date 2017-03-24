<?php
set_time_limit(0);
require_once "../Core/bootstrap.php";
include_once "../config/config.php";
include_once "../config/router.php";

$RedisAPI = new \Lib\RedisAPI();

print $RedisAPI->runScriptUpdate();






