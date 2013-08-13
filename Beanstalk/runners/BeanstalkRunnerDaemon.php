#!/usr/bin/php
<?php
#
# BeanstalkRunnerDaemon    Starts the PHP-Queue runner for ConvertEPubQueue
#
# chkconfig:    - 91 91
# description:    Runner for PHP-Queue
#

//require_once '/var/virtual/WebApps/storyzer_web/src/storyzer/Lib/Queue/Beanstalk' . '/config.php';
//$dir = '/var/virtual/WebApps/storyzer_web/src/storyzer/Lib/Queue/Beanstalk/runners';
require_once '/var/virtual/WebApps/test-q/Beanstalk' . '/config.php';
$dir = '/var/virtual/WebApps/test-q/Beanstalk/runners';
#require_once dirname(__DIR__) . '/config.php';
$worker = !empty($argv[2]) ? $argv[2] : 1;
$pid_file = sprintf('%s/process_w%s.pid', $dir, $worker);

if (empty($argv[1])) {
    Clio\Console::output("Unknown action.");
    die();
}
switch ($argv[1]) {
    case 'start':
        Clio\Console::stdout('Starting... ');
        try {
            Clio\Daemon::work(array(
                    'pid' => $pid_file,
                ),
                function($stdin, $stdout, $sterr) {
                    class BeanstalkRunner extends PHPQueue\Runner{}
                    // $dir = '/var/virtual/WebApps/storyzer_web/src/storyzer/Lib/Queue/Beanstalk/runners';
                    $dir = '/var/virtual/WebApps/test-q/Beanstalk/runners';
                    // the first BeanstalkSample refers to the Daemon
                    // the second BeanstlakSample refers to the Queue
                    $runner = new BeanstalkRunner('ConvertEPub', array('logPath'=>$dir . '/logs/'));
                    $runner->run();
                }
            );
            Clio\Console::output('%g[OK]%n');
        } catch (Exception $ex) {
            Clio\Console::output('%r[FAILED]%n');
        }
        break;
    case 'stop':
        Clio\Console::stdout('Stopping... ');
        try {
            Clio\Daemon::kill($pid_file, true);
            Clio\Console::output('%g[OK]%n');
        } catch (Exception $ex) {
            print_r($ex);
            Clio\Console::output('%r[FAILED]%n');
        }
        break;
    default:
        Clio\Console::output("Unknown action.");
        break;
}
