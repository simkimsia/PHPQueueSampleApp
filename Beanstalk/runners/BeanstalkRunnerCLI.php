<?php
require_once '/var/virtual/WebApps/storyzer_web/src/storyzer/Lib/Queue/Beanstalk' . '/config.php';
class BeanstalkRunner extends PHPQueue\Runner{}
$dir = '/var/virtual/WebApps/storyzer_web/src/storyzer/Lib/Queue/Beanstalk/runners';
$runner = new BeanstalkRunner('ConvertEPub', array('logPath'=>$dir . '/logs/'));
$runner->run();
?>