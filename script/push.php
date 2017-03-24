<?php
set_time_limit(0);
require_once "../Core/bootstrap.php";
include_once "../config/config.php";
include_once "../config/router.php";

$RedisAPI = new \Lib\RedisAPI();
for ($i=0; $i<200; $i++) {
        print $RedisAPI->runScriptUpdate();
}





